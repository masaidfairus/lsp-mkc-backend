<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk07Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        // return view('components.status-page', [
        //     'type' => 'danger',
        //     'title' => 'Menunggu Persetujuan',
        //     'message' => 'Status Anda saat ini masih dalam tinjauan admin/asesor.',
        //     'details' => ['Proses verifikasi biasanya memakan waktu 1-2 hari kerja.'],
        //     'button_text' => 'Kembali ke Dashboard',
        //     'button_url' => '../admin',
        // ]);

        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'plannings'
        ]);

        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;
        $ak07Data = $registration->plannings->pluck('ak07_data')->first();

        // Handle case where data is missing
        if (!$ak07Data) {
            $ak07Data = [];
        }

        $rawAssessmentDate = $ak07Data['assessment_date'] ?? now()->toDateString();
        $assessmentDate = $this->convertDateToLocalTime($rawAssessmentDate);

        $signatures = $ak07Data['signatures'] ?? [
            'asesi' => ['date' => now()],
            'asesor' => ['date' => now()],
        ];
        $signatureData = $this->getAssessorSignatureData($assessor);

        $data = [
            'scheme_name' => $scheme->name,
            'scheme_code' => $scheme->code,
            'scheme_type' => $scheme->type,
            'tuk' => $registration->tuk,
            'asesi_name' => strtoupper($candidate->name),
            'asesor_name' => $assessor->name,
            'assessment_date' => $assessmentDate,
            'asesi_potential' => $ak07Data['potential'] ?? [],
            'data_part_a' => $ak07Data['part_a'] ?? [],
            'data_part_b' => $ak07Data['part_b'] ?? [],
            'adjustment_results_a' => $ak07Data['adjustment_results_a'] ?? [],
            'adjustment_results_b' => $ak07Data['adjustment_results_b'] ?? [],
            // 'asesi_sign' => $signatures['asesi']['signature'],
            // 'asesor_sign' => $signatures['asesor']['signature'],
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'asesi_sign_date' => $this->convertDateToLocalTime($signatures['asesi']['date'] ?? now()),
            'asesor_sign_date' => $this->convertDateToLocalTime($signatures['asesor']['date'] ?? now()),
        ];

        $pdf = Pdf::loadView('forms.ak.ak07', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-07_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}