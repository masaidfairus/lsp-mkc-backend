<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Eager load relationships to prevent N+1 queries and ensure fast loading
        $registrations = AssessmentRegistration::with(['scheme', 'plannings', 'aplData', 'appeals', 'results'])
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

            // Jika status APL02 sudah complete, paksa step 6 (lanjut ke AK.04)
            if ($reg->status && $reg->status->isApl02Complete()) {
                $apl01NextStep = 6; 
            }

            $statusObj = $reg->status;
            
            // Logika Status Indonesia & Prerequisite
            $apl01Status = $statusObj ? $statusObj->apl01Value() : 'not_started';
            
            // ✅ Point 2: Jika sudah lewat Part 1 tapi belum submit, status = Belum Selesai
            if ($apl01Status === 'not_started' && $apl01NextStep > 1) {
                $apl01Status = 'incomplete';
            }

            $apl02Status = $statusObj ? $statusObj->apl02Value() : 'not_started';

            // Mapping Status ke Bahasa Indonesia
            $statusMap = [
                'not_started' => 'Belum dimulai',
                'incomplete'  => 'Belum selesai',
                'pending'     => ($reg->asesi_type === 'pribadi') ? 'Menunggu Pembayaran' : 'Sedang ditinjau',
                'paid'        => 'Sedang ditinjau',
                'approved'    => 'Diterima',
                'rejected'    => 'Ditolak',
                'submitted'   => 'Sedang ditinjau',
                'locked'      => 'Belum bisa diakses',
            ];

            // Prerequisite Logic
            $isApl01Approved = ($apl01Status === 'approved');
            $isApl02Approved = ($apl02Status === 'approved');
            
            $ak04Status = 'not_started';
            $appeal = $reg->appeals->first();
            if ($appeal) {
                $ak04Status = $appeal->status->value ?? 'submitted';
            }

            $finalApl02Status = $isApl01Approved ? $apl02Status : 'locked';
            $finalAk04Status  = ($isApl01Approved && $isApl02Approved) ? $ak04Status : 'locked';

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
                        'status' => $statusMap[$apl01Status] ?? $apl01Status,
                    ],
                    [
                        'code' => 'APL.02',
                        'label' => 'Asesmen Mandiri',
                        'status' => $statusMap[$finalApl02Status] ?? $finalApl02Status,
                    ],
                    [
                        'code' => 'AK.04',
                        'label' => 'Banding Asesmen',
                        'status' => ($finalAk04Status === 'pending') ? 'Sedang ditinjau' : ($statusMap[$finalAk04Status] ?? $finalAk04Status),
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
                        'is_available' => $reg->results->where('document_type', 'AK02')->isNotEmpty(),
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
            ];
        });

        return $this->successResponse(
            $data,
            'Data registrasi berhasil ditampilkan'
        );
    }

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