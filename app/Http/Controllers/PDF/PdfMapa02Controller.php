<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPlanning;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfMapa02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $planning = AssessmentPlanning::where('registration_id', $registration->id)->first();

        if (!$planning || empty($planning->mapa02_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen MAPA.02 Belum Siap', 'Dokumen FR.MAPA.02 belum dapat dicetak karena perencanaan asesmen belum disimpan.');
        }

        $mainData = $planning->mapa02_data;

        // Parse section
        $kelompokPekerjaan = [];
        $sections = $mainData['assessment_instruments_section'] ?? [];
        $groupIndex = 1;

        foreach ($sections as $section) {
            $groupName = $section['work_group'] ?? '';
            $units = $section['competency_units'] ?? [];
            $mappedUnits = [];
            foreach ($units as $u) {
                $mappedUnits[] = [
                    'kode' => $u['unit_code'] ?? '',
                    'judul' => $u['unit_title'] ?? '',
                ];
            }

            $instruments = $section['assessment_instruments'] ?? [];

            $kelompokPekerjaan[] = [
                'no' => $groupIndex++,
                'nama' => $groupName,
                'unit' => $mappedUnits,
                'assessment_instruments' => $instruments,
            ];
        }

        $signatures = $mainData['compiler_and_validator'] ?? [];
        $compilersData = $signatures['compiler'] ?? [];
        $validatorsData = $signatures['validator'] ?? [];

        $penyusunNama = $compilersData[0]['name'] ?? '';
        $penyusunReg = $compilersData[0]['reg_number'] ?? '';
        $penyusunDate = $compilersData[0]['date'] ?? '';
        $penyusunQr = $compilersData[0]['qr_code'] ?? null;
        if (!$penyusunQr && isset($compilersData[0]['signature'])) {
            // map signature to qr just so it displays, or map explicitly. 
            // The template uses $penyusun_qr_code for signature/qr code displaying
            $penyusunQr = $compilersData[0]['signature'];
        }

        $validatorNama = $validatorsData[0]['name'] ?? '';
        $validatorReg = $validatorsData[0]['reg_number'] ?? '';
        $validatorDate = $validatorsData[0]['date'] ?? '';
        $validatorQr = $validatorsData[0]['qr_code'] ?? null;
        if (!$validatorQr && isset($validatorsData[0]['signature'])) {
            $validatorQr = $validatorsData[0]['signature'];
        }


        $data = [
            'nama_skema' => $mainData['header']['certification_scheme'] ?? '',
            'kode_skema' => $mainData['header']['scheme_code'] ?? '',
            'kelompok_pekerjaan' => $kelompokPekerjaan,
            
            'penyusun_nama' => $penyusunNama,
            'penyusun_no_reg' => $penyusunReg,
            'penyusun_date' => $penyusunDate,
            'penyusun_qr_code' => $penyusunQr,
            
            'validator_nama' => $validatorNama,
            'validator_no_reg' => $validatorReg,
            'validator_date' => $validatorDate,
            'validator_qr_code' => $validatorQr,
        ];

                // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.mapa.mapa02', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
            
        $filename = 'FR_MAPA_02_' . str_replace([' ', '/', '\\'], '_', $mainData['header']['asesi_name'] ?? 'Asesi') . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }
}
