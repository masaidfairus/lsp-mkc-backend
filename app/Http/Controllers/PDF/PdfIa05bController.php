<?php

namespace App\Http\Controllers\PDF;

use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PdfIa05bController extends Controller
{
    /**
     * Menampilkan detail dari resource yang ditentukan.
     *
     * @param  Request  $request
     * @param  AssessmentRegistration  $registration
     * @return mixed
     */
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.05B')
            ->first();

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.05B Belum Siap', 'Dokumen FR.IA.05B belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $dataIa = $instrument->main_data ?? [];
        $header = $dataIa['header'] ?? [];
        $workGroups = $dataIa['work_groups'] ?? [];
        $answerKeys = $dataIa['answer_keys'] ?? [];
        $docControl = $dataIa['document_control'] ?? [];

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
                'nama' => $wg['group_name'] ?? '',
                'units' => $units,
            ];
        }

        $kunci_jawaban = [];
        foreach ($answerKeys as $ak) {
            $kunci_jawaban[] = [
                'no' => ($ak['question_no'] ?? '') . '.',
                'jawaban' => $ak['key'] ?? '',
            ];
        }

        // Signature lookup
        $asesorSig = $this->getUserSignature($registration->assessor?->id, $registration->id, 'IA.05b', true);
        $asesiSig = $this->getUserSignature($registration->candidate?->id, $registration->id, 'IA.05b');
        
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

        $data = [
            'judul' => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'nomor' => $header['scheme_number'] ?? $registration->scheme?->code ?? '-',
            'tuk'   => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',

            'kelompok' => $kelompok,
            'kunci_jawaban' => $kunci_jawaban,

            'penyusun' => $docControl['compilers'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],
            'validator' => $docControl['validators'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],
            
            'nama_asesi' => $registration->candidate?->name ?? '',
            'asesi_ttd_tanggal' => $asesiDate,
            'asesi_signature' => $asesiSignature,
            
            'nama_asesor'      => $asesorName,
            'asesor_reg'       => $asesorReg,
            'asesor_no_reg'    => $asesorReg,
            'asesor_ttd_tanggal' => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'tanggal'          => $header['assessment_date'] ?? now()->format('d-m-Y'),
        ];

        // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $registration->id, 'IA.05b');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $registration->id, 'IA.05b');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $registration->id, 'IA.05b');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $registration->id, 'IA.05b');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.ia.ia05b', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true);

        $filename = 'FR_IA_05B_' . str_replace([' ', '/', '\\'], '_', ($registration->candidate?->name ?? 'IA05B')) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }


}
