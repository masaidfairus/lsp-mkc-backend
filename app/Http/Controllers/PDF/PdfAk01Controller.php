<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk01Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'plannings'
        ]);

        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;
        $ak01Data = $registration->plannings->pluck('ak01_data')->first();

        // Handle case where data is missing
        if (!$ak01Data) {
            $ak01Data = [];
        }

        // Parse assessment schedule
        $assessmentSchedule = $ak01Data['assessment_schedule'] ?? null;
        $assessmentDate = '-';
        $time = '-';
        if ($assessmentSchedule) {
            $dt = Carbon::parse($assessmentSchedule);
            $assessmentDate = $this->convertDateToLocalTime($assessmentSchedule);
            $time = $dt->format('H:i');
        }

        $tuk = $ak01Data['tuk'] ?? $registration->tuk ?? '-';

        // Evidence mapping
        $evidenceSelections = (array) ($ak01Data['evidence_selections'] ?? []);
        $evidenceMap = [];
        foreach ($evidenceSelections as $sel) {
            if ($sel === 'Other') {
                $evidenceMap['Lainnya'] = true;
            } else {
                $evidenceMap[$sel] = true;
            }
        }

        $asesiSign = $ak01Data['signatures']['asesi'] ?? ['date' => now()];
        $asesorSign = $ak01Data['signatures']['asesor'] ?? ['date' => now()];

        $signatureData = $this->getAssessorSignatureData($assessor);

        $data = [
            'scheme_name' => $scheme->name,
            'scheme_code' => $scheme->code,
            'tuk' => $tuk,
            'asesi_name' => strtoupper($candidate->name),
            'asesor_name' => $assessor->name,
            'assessment_date' => $assessmentDate,
            'agreement_time' => $time . ' WIB',
            'agreement_tuk' => $tuk,
            'evidence_map' => $evidenceMap,
            'asesor_sign_date' => $this->convertDateToLocalTime($asesorSign['date'] ?? now()),
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'asesi_sign_date' => $this->convertDateToLocalTime($asesiSign['date'] ?? now()),
        ];

        $pdf = Pdf::loadView('forms.ak.ak01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}