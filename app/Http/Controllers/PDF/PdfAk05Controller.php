<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk05Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'reports'
        ]);

        $ak05Report = $registration->reports->where('document_type', 'FR.AK.05')->first();

        if (!$ak05Report || empty($ak05Report->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.05 Belum Siap', 'Dokumen FR.AK.05 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak05Data = $ak05Report->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        $signatureData = $this->getAssessorSignatureData($assessor);

        $assessorFooter = $ak05Data['assessor_footer'] ?? [];
        $assessorSignature = [
            'notes' => $assessorFooter['notes'] ?? '',
            'reg_number' => $signatureData['reg_number'],
            'date' => $assessorFooter['assessor_details']['date'] ?? '',
            'qr_preview' => $signatureData['qr_code'],
        ];

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'assessment_results' => $ak05Data['assessment_results'] ?? [],
            'evaluations' => $ak05Data['evaluation_section'] ?? [],
            'assessor_signature' => $assessorSignature,
        ];

        $pdf = Pdf::loadView('forms.ak.ak05', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-05_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
