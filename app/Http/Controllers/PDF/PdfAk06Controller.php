<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk06Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'reports'
        ]);

        $ak06Report = $registration->reports->where('document_type', 'FR.AK.06')->first();

        if (!$ak06Report || empty($ak06Report->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.06 Belum Siap', 'Dokumen FR.AK.06 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak06Data = $ak06Report->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        $signatureData = $this->getAssessorSignatureData($assessor);

        $assessorFooter = $ak06Data['assessor_footer'] ?? [];
        $assessorFooter['qr_preview'] = $signatureData['qr_code'];

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'principles' => $ak06Data['assessment_principles_review'] ?? [],
            'dimensions' => $ak06Data['competency_dimensions_review'] ?? [],
            'assessor_signature' => $assessorFooter,
        ];

        $pdf = Pdf::loadView('forms.ak.ak06', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-06_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
