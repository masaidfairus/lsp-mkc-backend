<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfMapa02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'scheme',
            'plannings'
        ]);

        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $mapa02_data = $registration->plannings->pluck('mapa02_data')->first();

        // Extract data from mapa02_data structure
        $header = $mapa02_data['header'] ?? [];
        $assessment_instruments_section = $mapa02_data['assessment_instruments_section'] ?? [];
        $compiler_and_validator = $mapa02_data['compiler_and_validator'] ?? [];

        // Transform assessment instruments section for blade
        $kelompok_pekerjaan = $this->transformAssessmentSection($assessment_instruments_section);

        $data = [
            // Header data
            'nama_skema' => $header['certification_scheme'] ?? $scheme->name,
            'kode_skema' => $header['scheme_code'] ?? $scheme->code,
            'assessment_date' => $header['assessment_date'] ?? '',
            'asesi_name' => $header['asesi_name'] ?? strtoupper($candidate->name),

            // Assessment section
            'kelompok_pekerjaan' => $kelompok_pekerjaan,

            // Penyusun & Validator
            'penyusun' => $compiler_and_validator['compiler'] ?? [],
            'validator' => $compiler_and_validator['validator'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.MAPA.mapa02', $data)->setPaper('a4', 'portrait');
        $filename = 'FR-MAPA-02_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Transform assessment instruments section for blade template
     */
    private function transformAssessmentSection($assessment_instruments_section)
    {
        $transformed = [];
        foreach ($assessment_instruments_section as $section) {
            $transformed[] = [
                'no' => count($transformed) + 1,
                'nama' => $section['work_group'] ?? 'Unknown',
                'unit' => $this->transformUnits($section['competency_units'] ?? []),
                'instrumen' => $section['assessment_instruments'] ?? [],
            ];
        }
        return $transformed;
    }

    /**
     * Transform competency units for blade template
     */
    private function transformUnits($competency_units)
    {
        $units = [];
        foreach ($competency_units as $unit) {
            $units[] = [
                'kode' => $unit['unit_code'] ?? '',
                'judul' => $unit['unit_title'] ?? '',
            ];
        }
        return $units;
    }
}