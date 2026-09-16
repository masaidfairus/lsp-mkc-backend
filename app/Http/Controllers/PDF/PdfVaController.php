<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentValidation;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfVaController extends Controller
{
    /**
     * Menampilkan detail dari resource yang ditentukan.
     *
     * @param  Request  $request
     * @param  AssessmentRegistration  $registration
     * @return mixed
     */
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validation = AssessmentValidation::where('registration_id', $registration->id)->first();

        // If the instrument is not generated yet, fallback
        if (!$validation || empty($validation->validation_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen FR.VA Belum Siap', 'Dokumen FR.VA belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['scheme', 'candidate', 'assessor']);

        $valData = $validation->validation_data ?? [];
        $header = $valData['header'] ?? [];
        $references = $valData['references'] ?? [];
        $prep = $valData['preparation'] ?? [];
        $contributions = $valData['contributions'] ?? [];
        
        $timValidasi = $header['validation_team'] ?? [];
        if (is_string($timValidasi)) {
            $timValidasi = explode(',', $timValidasi);
        }

        // Mapping helper for lists
        $mapToLabel = function($items) {
            if (!is_array($items)) return [];
            return array_map(fn($v) => ['label' => $v], $items);
        };

        $selectedFocus = $prep['focus_and_purpose'] ?? [];
        if (!empty($prep['has_focus_other_1']) && !empty($prep['focus_other_1'])) $selectedFocus[] = $prep['focus_other_1'];
        if (!empty($prep['has_focus_other_2']) && !empty($prep['focus_other_2'])) $selectedFocus[] = $prep['focus_other_2'];

        $selectedContext = $prep['validation_context'] ?? [];
        if (!empty($prep['has_context_other_1']) && !empty($prep['context_other_1'])) $selectedContext[] = $prep['context_other_1'];
        if (!empty($prep['has_context_other_2']) && !empty($prep['context_other_2'])) $selectedContext[] = $prep['context_other_2'];

        $selectedBench = $references['benchmarks'] ?? [];
        if (!empty($references['has_benchmark_other_1']) && !empty($references['benchmark_other_1'])) $selectedBench[] = $references['benchmark_other_1'];
        
        $selectedMaterials = $references['materials'] ?? [];
        if (!empty($references['has_material_other_1']) && !empty($references['material_other_1'])) $selectedMaterials[] = $references['material_other_1'];

        $selectedComm = $valData['communication_skills'] ?? [];
        if (!empty($valData['has_communication_other_1']) && !empty($valData['communication_other_1'])) $selectedComm[] = $valData['communication_other_1'];
        if (!empty($valData['has_communication_other_2']) && !empty($valData['communication_other_2'])) $selectedComm[] = $valData['communication_other_2'];

        $tujuanFokusOpts = [
            'Bagian dari Proses Penjaminan Mutu Organisasi', 'Mengantisipasi Risiko', 'Memenuhi Persyaratan BNSP',
            'Memastikan Kesesuaian Bukti', 'Meningkatkan Kualitas Asesmen', 'Mengevaluasi Kualitas Perangkat Asesmen',
            (!empty($prep['has_focus_other_1']) && !empty($prep['focus_other_1']) ? $prep['focus_other_1'] : '...'), 
            (!empty($prep['has_focus_other_2']) && !empty($prep['focus_other_2']) ? $prep['focus_other_2'] : '...')
        ];
        $konteksOpts = [
            'Internal Organisasi', 'Eksternal Organisasi', 'Proses Lisensi/ Re lisensi', 'Dengan Kolega Asesor', 'Kolega dari Organisasi Pelatihan atau Asesmen', 
            (!empty($prep['has_context_other_1']) && !empty($prep['context_other_1']) ? $prep['context_other_1'] : '...'), 
            (!empty($prep['has_context_other_2']) && !empty($prep['context_other_2']) ? $prep['context_other_2'] : '...')
        ];
        $pendekatanOpts = [
            'Panel asesmen', 'Pertemuan moderasi', 'Mengkaji perangkat asesmen', 'Acuan pembanding', 'Pengujian lapangan dan uji coba perangkat asesmen', 'Umpan balik dari klien', 'Mengkaji bukti-bukti'
        ];
        $acuanOpts = [
            'Standar Kompetensi (SKKNI/SKKK/SKI)', 'Skema Sertifikasi', 'SOP/IK', 'Manual Instruction / Book Manual', 'Standar Kinerja', 
            (!empty($references['has_benchmark_other_1']) && !empty($references['benchmark_other_1']) ? $references['benchmark_other_1'] : '...')
        ];
        $dokumenOpts = [
            'Perangkat Asesmen', 'Peraturan / Pedoman', 'Bukti-bukti hasil asesmen', 
            (!empty($references['has_material_other_1']) && !empty($references['material_other_1']) ? $references['material_other_1'] : '...')
        ];

        $mapWithOptions = function($selected, $options) {
            $selected = is_array($selected) ? $selected : [];
            
            // Legacy keys map
            $legacyMap = [
                'quality_assurance' => 'Bagian dari Proses Penjaminan Mutu Organisasi',
                'risk_anticipation' => 'Mengantisipasi Risiko',
                'bnsp_requirements' => 'Memenuhi Persyaratan BNSP',
                'evidence_consistency' => 'Memastikan Kesesuaian Bukti',
                'assessment_quality_improvement' => 'Meningkatkan Kualitas Asesmen',
                'evaluate_instrument_quality' => 'Mengevaluasi Kualitas Perangkat Asesmen',
                'competency_standards' => 'Standar Kompetensi (SKKNI/SKK/SKI)',
                'certification_scheme' => 'Skema Sertifikasi',
                'sop_ik' => 'SOP/IK',
                'manual_instruction' => 'Manual Instruction / Book Manual',
                'performance_standard' => 'Standar Kinerja',
                'assessment_instruments' => 'Perangkat Asesmen',
                'regulations_guidelines' => 'Peraturan / Pedoman',
                'assessment_results_evidence' => 'Bukti-bukti hasil asesmen',
            ];

            // Convert legacy keys to values if they exist
            $normalizedSelected = array_map(fn($v) => $legacyMap[$v] ?? $v, $selected);

            $result = [];
            foreach ($options as $opt) {
                $result[] = [
                    'label' => $opt,
                    'is_checked' => in_array($opt, $normalizedSelected)
                ];
            }
            return $result;
        };

        $tujuanFokus = $mapWithOptions($selectedFocus, $tujuanFokusOpts);
        $konteks = $mapWithOptions($selectedContext, $konteksOpts);
        $pendekatan = $mapWithOptions($prep['validation_approach'] ?? [], $pendekatanOpts);
        
        $acuan = $mapWithOptions($selectedBench, $acuanOpts);
        $dokumen = $mapWithOptions($selectedMaterials, $dokumenOpts);
        
        $komunikasiOpts = [
            'PRO AKTIF', 'ACTIVE LISTENING', 
            (!empty($valData['has_communication_other_1']) && !empty($valData['communication_other_1']) ? $valData['communication_other_1'] : '......'),
            (!empty($valData['has_communication_other_2']) && !empty($valData['communication_other_2']) ? $valData['communication_other_2'] : '......')
        ];
        $komunikasi = $mapWithOptions($selectedComm, $komunikasiOpts);

        $aspekValidasi = [];
        $matrix = $valData['validation_matrix'] ?? [];
        foreach ($matrix as $idx => $row) {
            $rules = (array) ($row['rules_of_evidence'] ?? []);
            $principles = (array) ($row['principles_of_assessment'] ?? []);
            
            $aspekValidasi[] = [
                'no' => $row['no'] ?? ($idx + 1),
                'label' => $row['aspect'] ?? '',
                'v' => in_array('v', $rules),
                'a' => in_array('a', $rules),
                't' => in_array('t', $rules),
                'm' => in_array('m', $rules),
                'pv' => in_array('v', $principles),
                'pr' => in_array('r', $principles),
                'pf' => in_array('f', $principles),
                'pa' => in_array('a', $principles),
            ];
        }

        $temuanRekomendasi = [];
        $temuanData = $contributions['findings_list'] ?? [];
        foreach ($temuanData as $idx => $item) {
            $temuanRekomendasi[] = [
                'no' => $idx + 1,
                'temuan' => $item['finding'] ?? '',
                'rekomendasi' => $item['recommendation'] ?? '',
            ];
        }

        $rencanaImpl = [];
        foreach ($valData['implementation_plan'] ?? [] as $idx => $impl) {
            $rencanaImpl[] = [
                'no' => $idx + 1,
                'kegiatan' => $impl['activity'] ?? '',
                'waktu' => $impl['completion_time'] ?? '',
                'penanggung_jawab' => $impl['person_in_charge'] ?? '',
            ];
        }

        // Map Orang Relevan - Show all options but check selected
        $roleOptions = [
            'Asesor Kompetensi (wajib)', 'Lead Asesor [Ketua TUK]', 'Manager, Supervisor',
            'Tenaga Ahli di bidangnya', 'Koordinator Pelatihan', 'Anggota Asosiasi Industry Profesi'
        ];
        
        $orangRelevan = [];
        $selectedRoles = $prep['relevant_roles'] ?? [];
        
        // Normalize selected roles to match new options
        $roleMapping = [
            'Manajer Sertifikasi' => 'Manager, Supervisor',
            'Lead Assessor' => 'Lead Asesor [Ketua TUK]',
            'Asesor' => 'Asesor Kompetensi (wajib)',
            'Validator' => 'Asesor Kompetensi (wajib)',
        ];
        $normalizedSelected = array_map(fn($r) => $roleMapping[$r] ?? $r, $selectedRoles);
        
        $confResult = $prep['confirmation_result'] ?? '';
        
        $relevantNamesRaw = $prep['relevant_names_combined'] ?? '';
        $relevantNamesFormatted = '';
        
        if (!empty($relevantNamesRaw)) {
            $names = [];
            if (is_array($relevantNamesRaw)) {
                $names = $relevantNamesRaw;
            } else {
                // Legacy support for string
                $names = preg_split('/[,\n\r]+/', $relevantNamesRaw, -1, PREG_SPLIT_NO_EMPTY);
            }
            
            $names = array_map('trim', $names);
            $formatted = [];
            foreach ($names as $idx => $name) {
                $formatted[] = ($idx + 1) . ". " . $name;
            }
            $relevantNamesFormatted = implode("\n", $formatted);
        }

        foreach ($roleOptions as $role) {
            $orangRelevan[] = [
                'label' => $role,
                'is_checked' => in_array($role, $normalizedSelected),
                'nama' => $relevantNamesFormatted,
                'hasil' => $confResult,
            ];
        }

        $data = [
            'header' => $header,
            'references' => $references,
            'preparation' => $prep,
            'contributions' => $contributions,
            'assessor' => $registration->assessor,
            'tujuanFokus' => $tujuanFokus,
            'konteks' => $konteks,
            'pendekatan' => $pendekatan,
            'acuan' => $acuan,
            'dokumen' => $dokumen,
            'komunikasi' => $komunikasi,
            'aspek_validasi' => $aspekValidasi,
            'temuan_rekomendasi' => $temuanRekomendasi,
            'rencana_implementasi' => $rencanaImpl ?? [],
            'tim_validasi' => $timValidasi,
            'hari_tanggal' => $header['day_date'] ?? now()->format('d-m-Y'),
            'tempat' => $header['location'] ?? 'TUK ' . ($registration->tuk ?? ''),
            'periode' => $header['period'] ?? [],
            'nama_skema' => $header['scheme_name'] ?? $registration->scheme?->name ?? '',
            'nomor_skema' => $header['scheme_number'] ?? $registration->scheme?->code ?? '',
            'orang_relevan' => $orangRelevan,
            'acuan_pembanding' => $acuan,
            'dokumen_terkait' => $dokumen,
        ];

        $pdf = Pdf::loadView('forms.mkva', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream('FR_VA_' . ($registration->candidate?->name ?? '') . '.pdf');
    }
}
