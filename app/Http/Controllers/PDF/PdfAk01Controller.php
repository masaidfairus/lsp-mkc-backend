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

        $rawAssessmentDate = $ak01Data['agreement_time']['date'];
        $time = $ak01Data['agreement_time']['time'];
        $tuk = $ak01Data['agreement_time']['tuk'];

        $assessmentDate = $this->convertDateToLocalTime($rawAssessmentDate);

        $evidenceMap = collect($ak01Data['evidence_methods'])->pluck('answer', 'label')->toArray();
        $asesiSign = $ak01Data['signatures']['asesi'];
        $asesorSign = $ak01Data['signatures']['asesor'];

        $data = [
            'scheme_name' => $scheme->name,
            'scheme_code' => $scheme->code,
            'tuk' => $registration->tuk,
            'asesi_name' => strtoupper($candidate->name),
            'asesor_name' => $assessor->name,
            'assessment_date' => $assessmentDate,
            'agreement_time' => $time . ' WIB',
            'agreement_tuk' => $tuk,
            'evidence_map' => $evidenceMap,
            'asesor_sign_date' => $this->convertDateToLocalTime($asesorSign['date']),
            'asesi_sign_date' => $this->convertDateToLocalTime($asesiSign['date']),
        ];

        $pdf = Pdf::loadView('forms.ak.ak01', $data)->setPaper('a4', 'portrait');
        $filename = 'AK-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}