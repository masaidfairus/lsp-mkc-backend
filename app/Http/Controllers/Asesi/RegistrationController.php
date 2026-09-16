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
        $registrations = AssessmentRegistration::with(['scheme', 'plannings', 'aplData', 'appeals', 'results', 'signatureRequests', 'instruments', 'assessor.profile'])
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

            // Cek tanda tangan admin untuk APL.01 (cek apakah ada yang berstatus signed)
            $isAdminSig01Complete = $reg->signatureRequests
                ->where('document_label', 'APL.01')
                ->where('role', 'Admin')
                ->where('status', 'signed')
                ->isNotEmpty();

            // Jika status APL01 sudah complete dan admin sudah TTD, paksa step 5 (lanjut ke APL02)
            if ($reg->status && $reg->status->isApl01Complete() && $isAdminSig01Complete) {
                $apl01NextStep = 5; 
            } else if ($reg->status && $reg->status->isApl01Complete()) {
                $apl01NextStep = 4; // Tetap di step 4 menunggu TTD Admin
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

            // Jika APL.01 diterima tapi Admin belum TTD
            if ($apl01Status === 'approved' && !$isAdminSig01Complete) {
                $apl01Status = 'waiting_admin_signature';
            }

            // Cek tanda tangan asesor untuk APL.02
            $isAssessorSig02Complete = $reg->signatureRequests
                ->where('document_label', 'APL.02')
                ->where('role', 'Asesor')
                ->where('status', 'signed')
                ->isNotEmpty();

            $apl02Status = $statusObj ? $statusObj->apl02Value() : 'not_started';
            
            if ($apl02Status === 'submitted') {
                $apl02Status = 'waiting_assessor';
            } elseif ($apl02Status === 'approved') {
                if ($isAssessorSig02Complete) {
                    $apl02Status = 'finished';
                } else {
                    $apl02Status = 'waiting_assessor';
                }
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
            $isApl01Approved = ($apl01Status === 'approved'); // Karena kalau belum TTD admin statusnya waiting_admin_signature
            $isApl02Approved = ($apl02Status === 'finished');

            // AK.02 harus sudah ada (dibuat asesor) sebelum AK.04 bisa diakses
            $ak02Exists = $reg->results->where('document_type', 'FR.AK.02')->isNotEmpty();

            $finalApl02Status = $isApl01Approved ? $apl02Status : 'locked';
            
            // AK.01 logic (Persetujuan Asesmen dan Kerahasiaan)
            $ak01Status = 'not_started';
            
            $sigs01 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                ->whereIn('document_label', ['AK.01', 'FR.AK.01'])
                ->get();
                
            $ak01Exists = $reg->plannings->whereNotNull('ak01_data')->isNotEmpty() || $sigs01->isNotEmpty();

            if ($ak01Exists || $isApl02Approved) {
                $asesiSig01 = $sigs01->where('role', 'asesi')->first();
                $assessorSig01 = $sigs01->whereIn('role', ['Asesor', 'asesor'])->first();

                if ($ak01Exists) {
                    if ($asesiSig01 && $asesiSig01->status === 'signed' && $assessorSig01 && $assessorSig01->status === 'signed') {
                        $ak01Status = 'finished';
                    } elseif ($assessorSig01 && $assessorSig01->status === 'signed') {
                        $ak01Status = 'waiting_signature';
                    } else {
                        $ak01Status = 'waiting_assessor';
                    }
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
                
            $ak02Exists = $reg->results->where('document_type', 'FR.AK.02')->isNotEmpty() || $sigs02->isNotEmpty();

            if ($ak02Exists || $ak01Status === 'finished') {
                $asesiSig02 = $sigs02->where('role', 'asesi')->first();
                $assessorSig02 = $sigs02->whereIn('role', ['Asesor', 'asesor'])->first();

                if ($ak02Exists) {
                    if ($asesiSig02 && $asesiSig02->status === 'signed' && $assessorSig02 && $assessorSig02->status === 'signed') {
                        $ak02ResultModelTemp = $reg->results->where('document_type', 'FR.AK.02')->first();
                        if ($ak02ResultModelTemp && isset($ak02ResultModelTemp->main_data['assessment_summary']['is_competent'])) {
                            $val = $ak02ResultModelTemp->main_data['assessment_summary']['is_competent'];
                            $ak02Status = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? 'kompeten' : 'belum_kompeten';
                        } else {
                            $ak02Status = 'finished';
                        }
                    } elseif ($assessorSig02 && $assessorSig02->status === 'signed') {
                        $ak02Status = 'waiting_signature';
                    } else {
                        $ak02Status = 'waiting_assessor';
                    }
                } else {
                    $ak02Status = 'waiting_assessor';
                }
            } else {
                $ak02Status = 'locked';
            }

            $ak02IsFinished = in_array($ak02Status, ['finished', 'kompeten', 'belum_kompeten']);

            // AK.03 (Umpan Balik Asesi)
            $sigs03 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                ->whereIn('document_label', ['AK.03', 'FR.AK.03'])
                ->get();
                
            $ak03Exists = $reg->results->where('document_type', 'FR.AK.03')->isNotEmpty() || $sigs03->isNotEmpty();
            $ak03Status = 'not_started';

            if ($ak03Exists || $ak02IsFinished) {
                $asesiSig03 = $sigs03->where('role', 'asesi')->first();
                $assessorSig03 = $sigs03->whereIn('role', ['Asesor', 'asesor'])->first();

                if ($ak03Exists) {
                    if ($asesiSig03 && $asesiSig03->status === 'signed' && $assessorSig03 && $assessorSig03->status === 'signed') {
                        $ak03Status = 'finished';
                    } elseif ($asesiSig03 && $asesiSig03->status === 'signed') {
                        $ak03Status = 'waiting_assessor';
                    } elseif ($assessorSig03 && $assessorSig03->status === 'signed') {
                        $ak03Status = 'waiting_signature';
                        if (!$asesiSig03) {
                            \App\Models\SignatureRequest::create([
                                'registration_id' => $reg->id,
                                'user_id'         => $reg->user_id,
                                'document_label'  => 'AK.03',
                                'status'          => 'pending',
                                'signable_type'   => $assessorSig03->signable_type,
                                'signable_id'     => $assessorSig03->signable_id,
                                'role'            => 'asesi',
                            ]);
                            // Note: It won't be returned in this immediate request's eager load, 
                            // but will be picked up on the next fetch.
                        }
                    } else {
                        $ak03Status = 'waiting_assessor';
                    }
                } else {
                    $ak03Status = 'not_started';
                }
            } else {
                $ak03Status = 'locked';
            }
            
            // AK.04 (Banding Asesmen)
            // Hanya tampil jika Asesi dinyatakan BELUM KOMPETEN atau sudah ada banding
            $ak04Exists = $reg->appeals->isNotEmpty();
            $ak02ResultModel = $reg->results->where('document_type', 'FR.AK.02')->first();
            $ak02IsCompetent = true; // default: anggap kompeten selama belum ada hasil
            $ak02HasResult = false;
            if ($ak02ResultModel && isset($ak02ResultModel->main_data['assessment_summary']['is_competent'])) {
                $val = $ak02ResultModel->main_data['assessment_summary']['is_competent'];
                $ak02IsCompetent = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                $ak02HasResult = true;
            }

            // Tampilkan AK.04 hanya jika:
            // - Sudah ada banding (ak04Exists), ATAU
            // - AK.02 selesai DAN hasilnya Belum Kompeten
            $showAk04 = $ak04Exists || ($ak02IsFinished && $ak02HasResult && $ak02IsCompetent === false);
            $ak04Status = $ak04Exists ? 'finished' : 'not_started';

            // AK.07 (Umpan Balik Asesor - butuh TTD Asesor & Asesi)
            $sigs07 = \App\Models\SignatureRequest::where('registration_id', $reg->id)
                ->whereIn('document_label', ['AK.07', 'FR.AK.07'])
                ->get();
                
            $ak07Exists = $reg->plannings->whereNotNull('ak07_data')->isNotEmpty() || $sigs07->isNotEmpty();
            $ak07Status = 'not_started';

            if ($ak07Exists || $ak02IsFinished) {
                $asesiSig07 = $sigs07->where('role', 'asesi')->first();
                $assessorSig07 = $sigs07->whereIn('role', ['Asesor', 'asesor'])->first();

                if ($ak07Exists) {
                    if ($asesiSig07 && $asesiSig07->status === 'signed' && $assessorSig07 && $assessorSig07->status === 'signed') {
                        $ak07Status = 'finished';
                    } elseif ($assessorSig07 && $assessorSig07->status === 'signed') {
                        $ak07Status = 'waiting_signature';
                        if (!$asesiSig07) {
                            \App\Models\SignatureRequest::create([
                                'registration_id' => $reg->id,
                                'user_id'         => $reg->user_id,
                                'document_label'  => 'AK.07',
                                'status'          => 'pending',
                                'signable_type'   => $assessorSig07->signable_type,
                                'signable_id'     => $assessorSig07->signable_id,
                                'role'            => 'asesi',
                            ]);
                        }
                    } else {
                        $ak07Status = 'waiting_assessor';
                    }
                } else {
                    $ak07Status = 'waiting_assessor';
                }
            } else {
                $ak07Status = 'locked';
            }

            if ($apl02Status === 'not_started') {
                $ak01Status = 'locked';
                $ak02Status = 'locked';
                $ak03Status = 'locked';
                $ak04Status = 'locked';
                $ak07Status = 'locked';
            }

            $formsList = [
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
            ];

            // Tambahkan AK.04 hanya jika relevan
            if ($showAk04) {
                $formsList[] = [
                    'code' => 'AK.04',
                    'label' => 'Banding Asesmen',
                    'status' => $ak04Status,
                ];
            }

            return [
                'id' => $reg->id,
                'scheme_name' => $reg->scheme?->name,
                'scheme_code' => $reg->scheme?->code,
                'status' => $statusObj ? $statusObj->getLabel() : 'Draft',
                'created_at' => $reg->created_at->toIso8601String(),
                'formatted_date' => $reg->created_at->translatedFormat('d F Y H:i'),
                'apl01_next_step' => $apl01NextStep,
                'asesi_type' => $reg->asesi_type ?? $profile?->asesi_type ?? 'pribadi',
                'assessor_phone' => $reg->assessor?->profile?->phone_number,
                'forms' => $formsList,
                'documents' => [
                    [
                        'code' => 'APL.01',
                        'title' => 'Formulir APL.01 (Permohonan)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('apl01.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: APL.01 approved oleh Admin DAN Admin sudah TTD
                        'is_available' => $reg->status_apl01->isApproved() && $isAdminSig01Complete,
                    ],
                    [
                        'code' => 'APL.02',
                        'title' => 'Formulir APL.02 (Asesmen Mandiri)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('apl02.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: APL.02 sudah disetujui (tidak perlu TTD tambahan)
                        'is_available' => $apl02Status === 'finished',
                    ],
                    [
                        'code' => 'AK.01',
                        'title' => 'Formulir AK.01 (Persetujuan Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak01.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: HANYA setelah KEDUA pihak (Asesor & Asesi) sudah TTD
                        'is_available' => $ak01Status === 'finished',
                    ],
                    [
                        'code' => 'AK.02',
                        'title' => 'Formulir AK.02 (Rekaman Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak02.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: HANYA setelah KEDUA pihak (Asesor & Asesi) sudah TTD
                        'is_available' => in_array($ak02Status, ['finished', 'kompeten', 'belum_kompeten']),
                    ],
                    [
                        'code' => 'AK.04',
                        'title' => 'Formulir AK.04 (Banding Asesmen)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak04.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: AK.02 sudah selesai DAN ada data banding
                        'is_available' => in_array($ak02Status, ['finished', 'kompeten', 'belum_kompeten']) && $ak04Exists,
                    ],
                    [
                        'code' => 'AK.07',
                        'title' => 'Formulir AK.07 (Umpan Balik)',
                        'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute('ak07.form', now()->addHours(2), ['registration' => $reg->id]),
                        // Ready: HANYA setelah KEDUA pihak (Asesor & Asesi) sudah TTD
                        'is_available' => $ak07Status === 'finished',
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
                    ->filter(function ($req) {
                        // Exclude orphaned signature requests whose parent signable was deleted
                        if ($req->signable_type && $req->signable_id) {
                            $modelClass = $req->signable_type;
                            if (class_exists($modelClass)) {
                                return $modelClass::find($req->signable_id) !== null;
                            }
                        }
                        return true; // Keep requests without signable (e.g. APL.01, APL.02)
                    })
                    ->map(function ($req) {
                        return [
                            'id' => $req->id,
                            'document_label' => $req->document_label,
                            'status' => $req->status,
                        ];
                    })->values()->toArray(),
                'exams' => $reg->instruments
                    ->whereIn('document_type', ['FR.IA.05A', 'FR.IA.06A'])
                    ->map(function ($formA) use ($reg) {
                        $typeC = str_replace('A', 'C', $formA->document_type);
                        $isCompleted = $reg->instruments->where('document_type', $typeC)->isNotEmpty();
                        
                        $mainData = is_array($formA->main_data) ? $formA->main_data : json_decode($formA->main_data ?? '{}', true);
                        $questionsCount = 0;
                        if ($formA->document_type === 'FR.IA.05A') {
                            $questionsCount = count(data_get($mainData, 'questions_list', []));
                        } elseif ($formA->document_type === 'FR.IA.06A') {
                            $questionsCount = count(data_get($mainData, 'questions', []));
                        }

                        return [
                            'id' => $formA->id,
                            'document_type' => $formA->document_type,
                            'title' => $formA->document_type === 'FR.IA.05A'
                                ? 'Daftar Pertanyaan Tertulis Pilihan Ganda'
                                : 'Daftar Pertanyaan Tertulis Esai',
                            'questions_count' => $questionsCount,
                            'status' => $isCompleted ? 'completed' : 'pending',
                        ];
                    })->values()->toArray(),
                'mkva' => $reg->instruments
                    ->whereIn('document_type', ['MKVA', 'FR.VA'])
                    ->map(function ($form) {
                        return [
                            'id' => $form->id,
                            'document_type' => $form->document_type,
                            'title' => 'Formulir Evaluasi MKVA',
                            'main_data' => is_array($form->main_data) ? $form->main_data : json_decode($form->main_data ?? '{}', true),
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