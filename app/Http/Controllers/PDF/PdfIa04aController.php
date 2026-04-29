<?php

namespace App\Http\Controllers\PDF;

use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PdfIa04aController extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.04A')
            ->first();

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.04A Belum Siap', 'Dokumen FR.IA.04A belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $dataIa = $instrument->main_data ?? [];
        $header = $dataIa['header'] ?? [];
        $workGroups = $dataIa['work_groups'] ?? [];
        $docControl = $dataIa['document_control'] ?? [];
        
        // Signature lookup
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);
        $asesiSig = $this->getUserSignature($registration->candidate?->id ?? null);

        $signatures = $dataIa['signatures'] ?? [];
        $asesorSigData = $signatures['assessor'] ?? [];
        $asesiSigData = $signatures['asesi'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['assessment_date'] ?? now()->format('d-m-Y');

        $asesiSignature  = $asesiSigData['signature'] ?? $asesiSig['signature'] ?? null;
        $asesiDate       = $asesiSigData['date'] ?? $header['assessment_date'] ?? now()->format('d-m-Y');

        $kelompok = [];
        foreach ($workGroups as $wg) {
            $units = [];
            foreach ($wg['units'] ?? [] as $u) {
                $units[] = [
                    'no' => $u['no'] ?? '',
                    'kode' => $u['unit_code'] ?? '',
                    'judul' => $u['unit_title'] ?? '',
                ];
            }
            $kelompok[] = [
                'no' => $wg['group_id'] ?? '',
                'nama' => $wg['group_name'] ?? '',
                'units' => $units,
                'skenario' => $wg['scenario_instructions']['content'] ?? '',
                'waktu_skenario' => $wg['scenario_instructions']['time_allocation'] ?? '',
                'hasil' => $wg['demonstration_items']['content'] ?? '',
                'waktu_hasil' => $wg['demonstration_items']['time_allocation'] ?? '',
                'umpan_balik' => $wg['assessee_feedback'] ?? '',
            ];
        }

        $data = [
            'judul' => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'nomor' => $header['scheme_code'] ?? $registration->scheme?->code ?? '-',
            'tuk'   => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor' => $header['assessor_name'] ?? $registration->assessor?->name ?? '-',
            'nama_asesi'  => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'     => $header['assessment_date'] ?? now()->format('d-m-Y'),

            'kelompok' => $kelompok,

            'penyusun' => $docControl['compilers'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],
            'validator' => $docControl['validators'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],

            'asesi_nama' => $registration->candidate?->name ?? '',
            'asesi_date' => $asesiDate,
            'asesi_signature' => $asesiSignature,

            'asesor_name'      => $asesorName,
            'asesor_reg'       => $asesorReg,
            'asesor_date'      => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,
        ];

        $pdf = Pdf::loadView('forms.IA.ia04a', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true);

        $filename = 'FR_IA_04A_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }

    private function getUserSignature($userId)
    {
        if (!$userId) {
            return ['signature' => null, 'qr_code' => null];
        }

        $sig = DB::table('user_signatures')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->first();

        return $sig ? [
            'signature' => $sig->signature,
            'qr_code'   => $sig->qr_code,
        ] : ['signature' => null, 'qr_code' => null];
    }
}
