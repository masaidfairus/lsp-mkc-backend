<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'results'
        ]);

        $ak02Result = $registration->results->where('document_type', 'FR.AK.02')->first();

        if (!$ak02Result || empty($ak02Result->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.02 Belum Siap', 'Dokumen FR.AK.02 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak02Data = $ak02Result->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        $assessmentDate = $this->convertDateToLocalTime($ak02Data['date'] ?? now()->format('Y-m-d'));
        
        $signatureData = $this->getAssessorSignatureData($assessor);

        if (isset($ak02Data['signatures']['assessor'])) {
            $ak02Data['signatures']['assessor']['qr_preview'] = $signatureData['qr_code'];
        }

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'assessment_date' => $assessmentDate,
            'competency_units' => $ak02Data['competency_units'] ?? [],
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'assessment_summary' => $ak02Data['assessment_summary'] ?? [],
            'signatures' => $ak02Data['signatures'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.ak.ak02', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-02_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
