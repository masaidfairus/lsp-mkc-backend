<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPlanning;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfMapa01Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $planning = AssessmentPlanning::where('registration_id', $registration->id)->first();

        if (!$planning || empty($planning->mapa01_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen MAPA.01 Belum Siap', 'Dokumen FR.MAPA.01 belum dapat dicetak karena perencanaan asesmen belum disimpan.');
        }

        $mainData = $planning->mapa01_data;

        // Parse KP / Unit
        $kelompokPekerjaan = [];
        $sections = $mainData['section_2_assessment_plan'] ?? [];
        $groupIndex = 1;
        $unitMap = [];

        foreach ($sections as $section) {
            $groupName = $section['work_group'] ?? '';
            $unitMap[$groupName][] = [
                'kode' => $section['unit_code'] ?? '',
                'judul' => $section['unit_title'] ?? '',
                'evidence' => $section['evidence'] ?? '',
                'evidence_type' => $section['evidence_type'] ?? [],
                'assessment_methods' => $section['assessment_methods'] ?? [],
            ];
        }

        foreach ($unitMap as $groupName => $units) {
            $kelompokPekerjaan[] = [
                'no' => $groupIndex++,
                'nama' => $groupName,
                'unit' => $units,
            ];
        }

        // Parse section 3
        $section3 = $mainData['section_3_contextualization'] ?? [];
        
        $karakteristik = 'Ada / Tidak Ada*';
        $kontekstualisasi = 'Ada / Tidak Ada*';
        $saran = 'Ada / Tidak Ada*';
        $penyesuaian = 'Ada / Tidak Ada*';
        $peluangTerintegrasi = 'Ada / Tidak Ada*';

        foreach ($section3 as $sec) {
            $no = $sec['no'] ?? '';
            $content = $sec['content'] ?? [];
            foreach ($content as $c) {
                $type = $c['type'] ?? '';
                $ans = $c['answer'] ?? '';
                if ($no === '3.1' && str_contains(strtolower($type), 'karakteristik')) {
                    $karakteristik = $ans;
                }
                if ($no === '3.1' && str_contains(strtolower($type), 'kontekstualisasi')) {
                    $kontekstualisasi = $ans;
                }
                if ($no === '3.2') {
                    $saran = $ans;
                }
                if ($no === '3.3') {
                    $penyesuaian = $ans;
                }
                if ($no === '3.4') {
                    $peluangTerintegrasi = $ans;
                }
            }
        }

        // Section 1
        $tujuanAsesmenArr = $mainData['section_1_approach']['tujuan_asesmen'] ?? [];
        $tujuanAsesmen = is_array($tujuanAsesmenArr) && count($tujuanAsesmenArr) > 0 ? $tujuanAsesmenArr[0] : '';
        $asesiOptions = $mainData['section_1_approach']['asesi_options'] ?? [];
        $lingkungan = $mainData['section_1_approach']['lingkungan'] ?? '';
        $peluangBukti = $mainData['section_1_approach']['peluang'] ?? '';
        $hubungan = $mainData['section_1_approach']['hubungan'] ?? '';
        $pelaku = $mainData['section_1_approach']['pelaku'] ?? '';
        $benchmarkStandards = $mainData['section_1_approach']['benchmark_standards'] ?? '';

        // TTD
        $signatures = $mainData['signatures'] ?? [];
        $compilersData = $signatures['compiler'] ?? [];
        $validatorsData = $signatures['validator'] ?? [];

        $penyusun = [
            ['nama' => $compilersData['penyusun_1_id']['name'] ?? '', 'nomor_met' => $compilersData['penyusun_1_id']['met_number'] ?? '', 'date' => $compilersData['penyusun_1_id']['date'] ?? '', 'signature' => $compilersData['penyusun_1_id']['signature'] ?? null, 'qr_code' => $compilersData['penyusun_1_id']['qr_code'] ?? null],
            ['nama' => $compilersData['penyusun_2_id']['name'] ?? '', 'nomor_met' => $compilersData['penyusun_2_id']['met_number'] ?? '', 'date' => $compilersData['penyusun_2_id']['date'] ?? '', 'signature' => $compilersData['penyusun_2_id']['signature'] ?? null, 'qr_code' => $compilersData['penyusun_2_id']['qr_code'] ?? null],
        ];

        $validator = [
            ['nama' => $validatorsData['validator_1_id']['name'] ?? '', 'nomor_met' => $validatorsData['validator_1_id']['met_number'] ?? '', 'date' => $validatorsData['validator_1_id']['date'] ?? '', 'signature' => $validatorsData['validator_1_id']['signature'] ?? null, 'qr_code' => $validatorsData['validator_1_id']['qr_code'] ?? null],
            ['nama' => $validatorsData['validator_2_id']['name'] ?? '', 'nomor_met' => $validatorsData['validator_2_id']['met_number'] ?? '', 'date' => $validatorsData['validator_2_id']['date'] ?? '', 'signature' => $validatorsData['validator_2_id']['signature'] ?? null, 'qr_code' => $validatorsData['validator_2_id']['qr_code'] ?? null],
        ];

        $relevantConfirmation = $mainData['section_4_relevant_confirmation'] ?? [];
        $orangRelevan = [];
        $roles = [
            'lsp_manager' => 'Manajer sertifikasi LSP',
            'master_assessor' => 'Master Asesor / Master Trainer / Lead Asesor Kompetensi',
            'training_manager' => 'Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar',
            'workplace_supervisor' => 'Manajer atau supervisor ditempat kerja'
        ];
        foreach ($roles as $key => $label) {
            $sigData = $relevantConfirmation[$key] ?? [];
            $orangRelevan[] = [
                'role' => $label,
                'nama' => $sigData['name'] ?? '',
                'date' => $sigData['date'] ?? '',
                'signature' => $sigData['signature'] ?? null,
            ];
        }

        $data = [
            'nama_skema' => $mainData['header']['certification_scheme'] ?? '',
            'kode_skema' => $mainData['header']['scheme_code'] ?? '',
            'tuk' => $mainData['header']['tuk'] ?? '',
            'tujuan_asesmen' => $tujuanAsesmen,
            'asesi_options' => $asesiOptions,
            'lingkungan' => $lingkungan,
            'peluang_bukti' => $peluangBukti,
            'hubungan' => $hubungan,
            'pelaku' => $pelaku,
            'benchmark_standards' => $benchmarkStandards,
            'kelompok_pekerjaan' => $kelompokPekerjaan,
            
            'karakteristik_khusus' => $karakteristik,
            'kebutuhan_kontekstualisasi' => $kontekstualisasi,
            'saran' => $saran,
            'penyesuaian_perangkat' => $penyesuaian,
            'peluang_terintegrasi' => $peluangTerintegrasi,
            
            'orang_relevan' => $orangRelevan,
            'penyusun' => $penyusun,
            'validator' => $validator,
        ];

        // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $registration->id, 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
                $c['date']      = $sig['is_signed'] ? $this->convertDateToLocalTime($sig['signed_at'] ?? now()) : '';
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $registration->id, 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
                $v['date']      = $sig['is_signed'] ? $this->convertDateToLocalTime($sig['signed_at'] ?? now()) : '';
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.mapa.mapa01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
            
        $filename = 'FR_MAPA_01_' . str_replace([' ', '/', '\\'], '_', $mainData['header']['asesi_name'] ?? 'Asesi') . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }
}
