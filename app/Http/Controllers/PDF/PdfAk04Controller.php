<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk04Controller extends Controller
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
            'appeals'
        ]);

        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;
        $ak04Data = $registration->appeals->pluck('ak04_data')->first();

        $rawAssessmentDate = $ak04Data['assessment_date'];
        $assessmentDate = $this->convertDateToLocalTime($rawAssessmentDate);
        $notes = $ak04Data['reason_notes'];

        $asesiSign = $ak04Data['signature'];
        $appealQuestions = collect($ak04Data['appeal_questions'])->pluck('answer', 'id')->toArray();
      
        $data = [
            'scheme_name' => $scheme->name,
            'scheme_code' => $scheme->code,
            'asesi_name' => strtoupper($candidate->name),
            'asesor_name' => $assessor->name,
            'assessment_date' => $assessmentDate,
            'appeal_questions' => $appealQuestions,
            'reason_notes' => $notes,
            'asesi_sign' => $asesiSign['asesi_signature'],
            'asesi_sign_date' => $this->convertDateToLocalTime($asesiSign['date']),
        ];

        $pdf = Pdf::loadView('forms.ak.ak04', $data)->setPaper('a4', 'portrait');
        $filename = 'AK-04_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}