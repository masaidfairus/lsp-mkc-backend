<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfIa09Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.09')
            ->first();

        // If the instrument is not generated yet, fallback
        if (!$instrument || empty($instrument->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.09 Belum Siap', 'Dokumen FR.IA.09 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['candidate', 'scheme', 'assessor']);

        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        $mainData = $instrument->main_data ?? [];
        $header   = $mainData['header'] ?? [];
        $portfolioList = $mainData['portfolio_evidence_list'] ?? [];
        $questions = $mainData['interview_questions'] ?? [];
        
        $docControl = $mainData['document_control'] ?? [];
        $asesorSigData = $mainData['signatures']['assessor'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['date'] ?? now()->format('d-m-Y');

        $formattedPortfolio = [];
        foreach ($portfolioList as $item) {
            $formattedPortfolio[] = [
                'no' => $item['no'] ?? '',
                'nama_bukti' => $item['evidence_name'] ?? '',
            ];
        }

        $formattedQuestions = [];
        foreach ($questions as $q) {
            $val = $q['achievement']['is_yes'] ?? null;
            $formattedQuestions[] = [
                'no' => $q['no'] ?? '',
                'pertanyaan' => $q['question'] ?? '',
                'kesimpulan_jawaban' => $q['assessee_answer_summary'] ?? '',
                'is_k' => filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
                'is_bk' => filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false,
            ];
        }

        $data = [
            'nama_skema'       => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema'       => $header['scheme_number'] ?? $registration->scheme?->code ?? '-',
            'tuk'              => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor'      => $header['assessor_name'] ?? $asesorName,
            'nama_asesi'       => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'          => $header['date'] ?? now()->format('d-m-Y'),
            
            'asesi_signature'  => $asesiSig['signature'] ?? null,
            'asesor_reg'       => $asesorReg,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'bukti_portofolio' => $formattedPortfolio,
            'pertanyaan_wawancara' => $formattedQuestions,

            'penyusun' => $docControl['compilers'] ?? [],
            'validator' => $docControl['validators'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.IA.ia09', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_09_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }

    private function getUserSignature($userId)
    {
        if (!$userId) return ['signature' => null, 'qr_code' => null];

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
