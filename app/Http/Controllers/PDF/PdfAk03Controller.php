<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk03Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'results'
        ]);

        $ak03Result = $registration->results->where('document_type', 'FR.AK.03')->first();

        if (!$ak03Result || empty($ak03Result->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.03 Belum Siap', 'Dokumen FR.AK.03 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak03Data = $ak03Result->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        $assessmentDate = $this->convertDateToLocalTime($ak03Data['date'] ?? now()->format('Y-m-d'));
        
        $signatureData = $this->getAssessorSignatureData($assessor);

        if (isset($ak03Data['signatures']['assessor'])) {
            $ak03Data['signatures']['assessor']['qr_preview'] = $signatureData['qr_code'];
        }

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'assessment_date' => $assessmentDate,
            'feedback_items' => $ak03Data['feedback_items'] ?? [],
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'notes' => $ak03Data['notes'] ?? '',
            'signatures' => $ak03Data['signatures'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.ak.ak03', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-03_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
