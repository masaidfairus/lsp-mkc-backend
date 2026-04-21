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

        $registrations = AssessmentRegistration::where('user_id', $user->id)
            ->with(['scheme:id,name,code', 'plannings'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($reg) {
                $planning = $reg->plannings->first();

                // ── Status AK.01 ──────────────────────────────────
                $ak01Status = 'draft';
                if ($planning && $planning->ak01_data) {
                    $asesiSig = $planning->ak01_data['signatures']['asesi']['signature'] ?? null;
                    $asesorSig = $planning->ak01_data['signatures']['asesor']['signature'] ?? null;

                    if ($asesiSig && $asesorSig) {
                        $ak01Status = 'approved';
                    } elseif ($asesiSig) {
                        $ak01Status = 'submitted';
                    } elseif ($asesorSig) {
                        $ak01Status = 'pending';
                    }
                }

                // ── Status AK.04 ──────────────────────────────────
                $ak04Status = 'draft';
                if ($planning && $planning->ak04_data) {
                    $asesiSig = $planning->ak04_data['signatures']['asesi']['signature'] ?? null;
                    $ak04Status = $asesiSig ? 'submitted' : 'pending';
                }

                // ── Status AK.07 ──────────────────────────────────
                $ak07Status = 'draft';
                if ($planning && $planning->ak07_data) {
                    $asesiSig = $planning->ak07_data['signatures']['asesi']['signature'] ?? null;
                    $ak07Status = $asesiSig ? 'submitted' : 'pending';
                }

                return [
                    'registration_id' => $reg->id,
                    'scheme_name'     => $reg->scheme?->name ?? '-',
                    'scheme_code'     => $reg->scheme?->code ?? '-',
                    'created_at'      => $reg->created_at?->format('Y-m-d'),
                    'forms'           => [
                        [
                            'code'   => 'APL.01',
                            'label'  => 'Permohonan Sertifikasi',
                            'status' => $reg->status_apl01?->value ?? 'draft',
                        ],
                        [
                            'code'   => 'APL.02',
                            'label'  => 'Asesmen Mandiri',
                            'status' => $reg->status_apl02?->value ?? 'draft',
                        ],
                        [
                            'code'   => 'AK.01',
                            'label'  => 'Persetujuan Asesmen',
                            'status' => $ak01Status,
                        ],
                        [
                            'code'   => 'AK.04',
                            'label'  => 'Banding Asesmen',
                            'status' => $ak04Status,
                        ],
                        [
                            'code'   => 'AK.07',
                            'label'  => 'Ceklis Penyesuaian',
                            'status' => $ak07Status,
                        ],
                    ],
                ];
            });

        return $this->successResponse(
            $registrations,
            'Data registrasi berhasil ditampilkan'
        );
    }
}