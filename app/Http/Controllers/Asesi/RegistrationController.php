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
        $registrations = AssessmentRegistration::with(['scheme', 'plannings', 'aplData'])
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
                    // Bukti dasar disimpan di file_profiles, match dengan basic_requirement_proofs
                    $basicReqListIds = \App\Models\BasicRequirementProof::where('scheme_id', $reg->scheme_id)
                        ->pluck('list_id')
                        ->toArray();

                    $hasBasicProof = !empty($basicReqListIds) && \App\Models\FileProfile::where('user_id', $user->id)
                        ->whereIn('list_id', $basicReqListIds)
                        ->exists();

                    if ($hasBasicProof) {
                        $apl01NextStep = 4;

                        // Cek apakah sudah upload bukti administratif (Bagian 4 done = submitted)
                        // Status APL01 sudah pending/approved berarti semua step selesai
                        if ($reg->status && $reg->status->isApl01Complete()) {
                            $apl01NextStep = 5;
                        }
                    }
                }
            }

            // Jika status APL01 sudah complete, paksa step 5 (lanjut ke APL02)
            if ($reg->status && $reg->status->isApl01Complete()) {
                $apl01NextStep = 5; 
            }

            return [
                'registration_id' => $reg->id,
                'scheme_id' => $reg->scheme_id,
                'scheme_name' => $reg->scheme?->name ?? 'Pilih Skema Sertifikasi',
                'scheme_code' => $reg->scheme?->code ?? '-',
                'candidate_name' => $reg->candidate?->name ?? '-',
                'created_at' => $reg->created_at?->toDateTimeString(),
                'apl01_next_step' => $apl01NextStep,
                'status' => $reg->status,
                'forms' => [
                    [
                        'code' => 'APL.01',
                        'label' => 'Permohonan Sertifikasi',
                        'status' => $reg->status?->apl01Value() ?? 'not_started',
                    ],
                    [
                        'code' => 'APL.02',
                        'label' => 'Asesmen Mandiri',
                        'status' => $reg->status?->apl02Value() ?? 'not_started',
                    ],
                    [
                        'code' => 'AK.01',
                        'label' => 'Persetujuan Asesmen',
                        'status' => 'not_started',
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