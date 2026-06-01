<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Menampilkan daftar resource.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Eager load relationships to prevent N+1 queries and ensure fast loading
        $registrations = AssessmentRegistration::with(['scheme', 'plannings', 'aplData', 'appeals', 'results', 'signatureRequests'])
            ->where('user_id', $user->id)
            ->whereNotNull('scheme_id')
            ->latest()
            ->get();

        $data = $registrations->map(function ($reg) use ($user) {
            $profile = $reg->candidate?->profile;

            // ── APL01 Next Step Logic ────────────────────────
            $apl01NextStep = 1;

            // Jika sudah ada data APL01 (Bagian 1 done)
            if ($reg->aplData()->exists()) {
                $apl01NextStep = 2;

                // Jika sudah ada tipe asesi DI PENDAFTARAN (Bagian 2 done)
                if ($reg->asesi_type) {
                    $apl01NextStep = 3;

                    // Cek apakah sudah upload bukti dasar (Bagian 3 done)
                    $basicReqListIds = \App\Models\BasicRequirementProof::where('scheme_id', $reg->scheme_id)
                        ->where('is_mandatory', true)
                        ->pluck('list_id')
                        ->toArray();

                    $uploadedBasicIds = \App\Models\FileProfile::where('user_id', $user->id)
                        ->whereIn('list_id', $basicReqListIds)
                        ->pluck('list_id')
                        ->toArray();

                    // Cek juga OR groups
                    $orGroups = \App\Models\BasicRequirementProof::where('scheme_id', $reg->scheme_id)
                        ->whereNotNull('or_group')
                        ->get()
                        ->groupBy('or_group');

                    $orGroupFulfilled = true;
                    foreach ($orGroups as $groupItems) {
                        $groupListIds = collect($groupItems)->pluck('list_id')->toArray();
                        $hasOneInGroup = \App\Models\FileProfile::where('user_id', $user->id)
                            ->whereIn('list_id', $groupListIds)
                            ->exists();
                        if (!$hasOneInGroup) {
                            $orGroupFulfilled = false;
                            break;
                        }
                    }

                    // Mandatory fulfilled if all mandatory IDs are uploaded AND OR groups are fulfilled
                    $mandatoryFulfilled = (count(array_intersect($basicReqListIds, $uploadedBasicIds)) === count($basicReqListIds)) && $orGroupFulfilled;

                    if ($mandatoryFulfilled && !empty($basicReqListIds)) {
                        $apl01NextStep = 4;
                    }
                }
            }

            // Jika status APL01 sudah complete, paksa step 5 (lanjut ke APL02)
            if ($reg->status && $reg->status->isApl01Complete()) {
                $apl01NextStep = 5; 
            }

            // Jika status APL02 sudah complete, paksa step 6 (lanjut ke AK.02)
            if ($reg->status && $reg->status->isApl02Complete()) {
                $apl01NextStep = 6; 
            }

            // Jika AK.02 sudah dibuat asesor, paksa step 7 (lanjut ke AK.04)
            $ak02Exists = $reg->results->where('document_type', 'FR.AK.02')->isNotEmpty();
            if ($reg->status && $reg->status->isApl02Complete() && $ak02Exists) {
                $apl01NextStep = 7;
            }

            $statusObj = $reg->status;
            
            // Logika Status Indonesia & Prerequisite
            $apl01Status = $statusObj ? $statusObj->apl01Value() : 'not_started';
            
            // ✅ Point 2: Jika sudah lewat Part 1 tapi belum submit, status = Belum Selesai
            if ($apl01Status === 'not_started' && $apl01NextStep > 1) {
                $apl01Status = 'incomplete';
            }

            $apl02Status = $statusObj ? $statusObj->apl02Value() : 'not_started';
            // 'approved' from admin panel = APL-02 Diterima → map to 'finished'
            if ($apl02Status === 'approved') {
                $apl02Status = 'finished';
            }

            // Mapping Status ke Bahasa Indonesia
            $statusMap = [
                'not_started'       => 'Belum dimulai',
                'incomplete'        => 'Belum selesai',
                'pending'           => ($reg->asesi_type === 'pribadi') ? 'Menunggu Pembayaran' : 'Sedang ditinjau',
                'paid'              => 'Sedang ditinjau',
                'approved'          => 'Diterima',
                'finished'          => 'Selesai',
                'rejected'          => 'Ditolak',
                'submitted'         => 'Sedang ditinjau',
                'locked'            => 'Belum bisa diakses',
                'waiting_assessor'  => 'Menunggu Asesor',
                'waiting_signature' => 'Butuh TTD Asesi',
            ];

            // Prerequisite Logic
            $isApl01Approved = ($apl01Status === 'approved');
            $isApl02Approved = ($apl02Status === 'finished');

            // AK.02 harus sudah ada (dibuat asesor) sebelum AK.04 bisa diakses
            $ak02Exists = $reg->results->where('document_type', 'FR.AK.02')->isNotEmpty();

            $finalApl02Status = $isApl01Approved ? $apl02Status : 'locked';
            
            // AK.01 logic (Persetujuan Asesmen dan Kerahasiaan)
            $ak01Status = 'not_started';
            if ($isApl02Approved) {
                // Check signature status from database directly to ensure accuracy
                $sigs01 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                    ->whereIn('document_label', ['AK.01', 'FR.AK.01'])
                    ->get();

                $asesiSig01 = $sigs01->where('role', 'asesi')->first();
                $assessorSig01 = $sigs01->whereIn('role', ['Asesor', 'asesor'])->first();

                if ($asesiSig01 && $asesiSig01->status === 'signed' && $assessorSig01 && $assessorSig01->status === 'signed') {
                    $ak01Status = 'finished';
                } elseif ($assessorSig01 && $assessorSig01->status === 'signed') {
                    $ak01Status = 'waiting_signature';
                } else {
                    $ak01Status = 'waiting_assessor';
                }
            } else {
                $ak01Status = 'locked';
            }

            // AK.02 logic (Rekaman Asesmen)
            $ak02Status = 'not_started';
            
            $sigs02 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
                ->get();

            $asesiSig02 = $sigs02->where('role', 'asesi')->first();
            $assessorSig02 = $sigs02->whereIn('role', ['Asesor', 'asesor'])->first();

            if ($asesiSig02 || $assessorSig02 || $ak02Exists) {
                if ($asesiSig02 && $asesiSig02->status === 'signed' && $assessorSig02 && $assessorSig02->status === 'signed') {
                    $ak02Status = 'finished';
                } elseif ($assessorSig02 && $assessorSig02->status === 'signed') {
                    $ak02Status = 'waiting_signature';
                } else {
                    $ak02Status = 'waiting_assessor';
                }
            } else {
                if ($ak01Status === 'finished') {
                    $ak02Status = 'waiting_assessor';
                } else {
                    $ak02Status = 'locked';
                }
            }

            // AK.03 (Umpan Balik Asesi)
            $ak03Exists = $reg->results->where('document_type', 'FR.AK.03')->isNotEmpty();
            $ak03Status = 'not_started';

            $sigs03 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                ->whereIn('document_label', ['AK.03', 'FR.AK.03'])
                ->get();

            $asesiSig03 = $sigs03->where('role', 'asesi')->first();
            $assessorSig03 = $sigs03->whereIn('role', ['Asesor', 'asesor'])->first();

            if ($asesiSig03 || $assessorSig03 || $ak03Exists) {
                if ($asesiSig03 && $asesiSig03->status === 'signed' && $assessorSig03 && $assessorSig03->status === 'signed') {
                    $ak03Status = 'finished';
                } elseif ($assessorSig03 && $assessorSig03->status === 'signed') {
                    $ak03Status = 'waiting_signature';
                } else {
                    $ak03Status = 'waiting_assessor';
                }
            } else {
                if ($ak02Status === 'finished') {
                    $ak03Status = 'waiting_assessor';
                } else {
                    $ak03Status = 'locked';
                }
            }
            
            // AK.04 (Banding Asesmen)
            $ak04Exists = $reg->appeals->isNotEmpty();
            $ak04Status = ($ak02Status === 'finished') ? ($ak04Exists ? 'finished' : 'not_started') : 'locked';

            return [
                'id' => $reg->id,
                'scheme_name' => $reg->scheme?->name,
                'scheme_code' => $reg->scheme?->code,
                'status' => $statusObj ? $statusObj->getLabel() : 'Draft',
                'created_at' => $reg->created_at->toIso8601String(),
                'formatted_date' => $reg->created_at->translatedFormat('d F Y H:i'),
                'apl01_next_step' => $apl01NextStep,
                'forms' => [
                    [
                        'code' => 'APL.01',
                        'label' => 'Permohonan Sertifikasi',
                        'status' => $apl01Status,
                    ],
                    [
                        'code' => 'APL.02',
                        'label' => 'Asesmen Mandiri',
                        'status' => $finalApl02Status,
                    ],
                    [
                        'code' => 'AK.01',
                        'label' => 'Persetujuan Asesmen',
                        'status' => $ak01Status,
                    ],
                    [
                        'code' => 'AK.02',
                        'label' => 'Rekaman Asesmen',
                        'status' => $ak02Status,
                    ],
                    [
                        'code' => 'AK.03',
                        'label' => 'Umpan Balik Asesi',
                        'status' => $ak03Status,
                    ],
                    [
                        'code' => 'AK.04',
                        'label' => 'Banding Asesmen',
                        'status' => $ak04Status,
                    ],
                ],
                'documents' => [
                    [
                        'code' => 'APL.01',
                        'title' => 'Formulir APL.01 (Permohonan)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('apl01.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $reg->status_apl01->isApproved(),
                    ],
                    [
                        'code' => 'APL.02',
                        'title' => 'Formulir APL.02 (Asesmen Mandiri)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('apl02.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $reg->status_apl02->isApproved(),
                    ],
                    [
                        'code' => 'AK.01',
                        'title' => 'Formulir AK.01 (Persetujuan Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak01.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $reg->plannings->whereNotNull('ak01_data')->isNotEmpty(),
                    ],
                    [
                        'code' => 'AK.02',
                        'title' => 'Formulir AK.02 (Rekaman Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak02.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $ak02Status === 'finished', // ✅ Only after BOTH parties signed
                    ],
                    [
                        'code' => 'AK.04',
                        'title' => 'Formulir AK.04 (Banding Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak04.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $reg->appeals->isNotEmpty(),
                    ],
                    [
                        'code' => 'AK.07',
                        'title' => 'Formulir AK.07 (Umpan Balik)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak07.form', now()->addHours(2), ['registration' => $reg->id]),
                        'is_available' => $reg->plannings->whereNotNull('ak07_data')->isNotEmpty(),
                    ],
                    [
                        'code' => 'CERT',
                        'title' => 'Sertifikat Kompetensi',
                        'url' => $reg->certificate ? asset('storage/' . $reg->certificate) : null,
                        'is_available' => $reg->certificate !== null,
                    ],
                ],
                'signature_requests' => $reg->signatureRequests
                    ->where('role', 'asesi')
                    ->where('status', 'pending')
                    ->map(function ($req) {
                        return [
                            'id' => $req->id,
                            'document_label' => $req->document_label,
                            'status' => $req->status,
                        ];
                    })->values()->toArray(),
            ];
        });

        return $this->successResponse(
            $data,
            'Data registrasi berhasil ditampilkan'
        );
    }

    /**
     * Menghapus resource yang ditentukan dari penyimpanan.
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function destroy($id)
{
    $user = request()->user();

    $registration = AssessmentRegistration::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$registration) {
        return response()->json([
            'message' => 'Pengajuan tidak ditemukan atau bukan milik Anda'
        ], 404);
    }

    // Opsional: Cek apakah boleh dibatalkan
    if ($registration->status?->isApl01Complete()) {
        return response()->json([
            'message' => 'Pengajuan yang sudah diproses tidak dapat dibatalkan'
        ], 403);
    }

    $registration->delete();

    return response()->json([
        'message' => 'Pengajuan berhasil dibatalkan'
    ]);
}
}