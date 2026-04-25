<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfApl02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'scheme.competencyUnits.competencyElements',
            'selfAssessments',
            'portfolioFiles'
        ]);

        $candidate = $registration->candidate;
        $userId = $candidate->id;
        $scheme = $registration->scheme;
        $schemeId = $scheme->id;
        $assessor = $registration->assessor;
        $selfAssessments = collect($registration->selfAssessments)->keyBy('element_id');

        // Ambil file secara manual lewat Model FileProfile agar filter lebih transparan
        $portfolios = \App\Models\FileProfile::where('user_id', $userId)
            ->whereHas('documentList.portfolioScheme', function ($q) use ($schemeId) {
                $q->where('scheme_id', $schemeId);
            })
            ->get();

        $data = [
            'nama_skema' => $scheme->name,
            'kode_skema' => $scheme->code,
            'nama_asesi' => strtoupper($candidate->name),
            'nama_asesor' => $assessor->name,
            'no_reg_asesor' => $assessor->registration_number,
            'unit_kompetensi' => $scheme->competencyUnits ?? [],
            'asesmen_mandiri' => $selfAssessments ?? [],
            'portfolios' => $portfolios,
        ];

        $pdf = Pdf::loadView('forms.APL.apl02', $data)->setPaper('a4', 'portrait');
        $filename = 'APL-02_' . str_replace(' ', '_', $request->nama_asesi) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}