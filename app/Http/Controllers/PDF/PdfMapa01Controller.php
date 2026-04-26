<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfMapa01Controller extends Controller
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
        $mapa01_data = $registration->plannings->pluck('mapa01_data')->first();

        // Extract data from mapa01_data structure
        $header = $mapa01_data['header'] ?? [];
        $section_1 = $mapa01_data['section_1_approach'] ?? [];
        $section_2 = $mapa01_data['section_2_assessment_plan'] ?? [];
        $section_3 = $mapa01_data['section_3_contextualization'] ?? [];
        $section_4 = $mapa01_data['section_4_relevant_confirmation'] ?? [];
        $signatures = $mapa01_data['signatures'] ?? [];

        // Group section 2 data by work group
        $kelompok_pekerjaan = $this->groupByWorkGroup($section_2);

        $data = [
            // Header data
            'nama_skema' => $header['certification_scheme'] ?? $scheme->name,
            'kode_skema' => $header['scheme_code'] ?? $scheme->code,
            'tuk' => $header['tuk'] ?? $registration->tuk,
            'assessment_date' => $header['assessment_date'] ?? '',
            'asesi_name' => $header['asesi_name'] ?? strtoupper($candidate->name),

            // Section 1: Assessment Approach
            'asesi_option' => $section_1['asesi_options'] ?? '',
            'tujuan_asesmen' => $section_1['tujuan_asesmen'] ?? '',
            'lingkungan' => $section_1['lingkungan'] ?? '',
            'peluang' => $section_1['peluang'] ?? '',
            'hubungan' => $section_1['hubungan'] ?? '',
            'pelaku_asesmen' => $section_1['pelaku'] ?? '',
            'benchmark_standards' => $section_1['benchmark_standards'] ?? '',

            // Section 2: Assessment Plan
            'kelompok_pekerjaan' => $kelompok_pekerjaan,

            // Section 3: Contextualization
            'karakteristik_khusus' => $this->extractContextValue($section_3, '3.1', 'a. Karakteristik Kandidat:'),
            'kebutuhan_kontekstualisasi' => $this->extractContextValue($section_3, '3.1', 'b. Kebutuhan kontekstualisasi terkait tempat kerja:'),
            'saran_pelatihan' => $this->extractContextValue($section_3, '3.2', 'Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan'),
            'penyesuaian_perangkat' => $this->extractContextValue($section_3, '3.3', 'Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi'),
            'peluang_terintegrasi' => $this->extractContextValue($section_3, '3.4', 'Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen'),

            // Section 4: Relevant confirmation
            'lsp_manager' => $section_4['lsp_manager'] ?? [],
            'master_assessor' => $section_4['master_assessor'] ?? [],
            'training_manager' => $section_4['training_manager'] ?? [],
            'workplace_supervisor' => $section_4['workplace_supervisor'] ?? [],

            // Signatures
            'penyusun' => [
                $signatures['compiler']['penyusun_1_id'] ?? [],
                $signatures['compiler']['penyusun_2_id'] ?? [],
            ],
            'validator' => [
                $signatures['validator']['validator_1_id'] ?? [],
                $signatures['validator']['validator_2_id'] ?? [],
            ],
        ];

        $pdf = Pdf::loadView('forms.MAPA.mapa01', $data)->setPaper('a4', 'portrait');
        $filename = 'FR-MAPA-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Group assessment units by work group
     */
    private function groupByWorkGroup($section_2)
    {
        $grouped = [];
        foreach ($section_2 as $item) {
            $work_group = $item['work_group'] ?? 'Unknown';
            if (!isset($grouped[$work_group])) {
                $grouped[$work_group] = [
                    'nama' => $work_group,
                    'no' => count($grouped) + 1,
                    'unit' => [],
                ];
            }
            $grouped[$work_group]['unit'][] = [
                'kode' => $item['unit_code'] ?? '',
                'judul' => $item['unit_title'] ?? '',
                'bukti' => $item['evidence'] ?? '',
                'jenis_bukti' => $item['evidence_type'] ?? [],
                'metode' => $item['assessment_methods'] ?? [],
                'jenis_bukti_select' => $item['evidence_type_select'] ?? '',
            ];
        }
        return array_values($grouped);
    }

    /**
     * Extract context value from section 3 by no and type
     */
    private function extractContextValue($section_3, $no, $type)
    {
        foreach ($section_3 as $section) {
            if ($section['no'] === $no) {
                foreach ($section['content'] as $content) {
                    if ($content['type'] === $type) {
                        return $content['answer'] ?? '';
                    }
                }
            }
        }
        return '';
    }
}