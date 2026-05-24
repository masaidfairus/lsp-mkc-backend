<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk07Controller extends Controller
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

        if (!$assessor) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen AK.07 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }
        $ak07Data = $registration->plannings->pluck('ak07_data')->first();

        if (!$ak07Data) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.07 Belum Siap', 'Data dokumen AK.07 belum diisi. Silakan lengkapi terlebih dahulu melalui menu yang sesuai.');
        }

        $rawAssessmentDate = $ak07Data['assessment_date'] ?? now()->toDateString();
        $assessmentDate = $this->convertDateToLocalTime($rawAssessmentDate);

        $signatures = $ak07Data['signatures'] ?? [
            'asesi' => ['date' => now()],
            'asesor' => ['date' => now()],
        ];
        $signatureData = $this->getAssessorSignatureData($assessor, $registration->id, 'AK.07', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$signatureData['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen AK.07 Belum Ditandatangani', 
                'Dokumen FR.AK.07 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        $data = [
            'scheme_name' => $scheme->name,
            'scheme_code' => $scheme->code,
            'scheme_type' => $scheme->type,
            'tuk' => $registration->tuk,
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'assessment_date' => $assessmentDate,
            'asesi_potential' => $ak07Data['potential'] ?? [],
            'data_part_a' => $ak07Data['part_a'] ?? [],
            'data_part_b' => $ak07Data['part_b'] ?? [],
            'adjustment_results_a' => $ak07Data['adjustment_results_a'] ?? [],
            'adjustment_results_b' => $ak07Data['adjustment_results_b'] ?? [],
            // 'asesi_sign' => $signatures['asesi']['signature'],
            // 'asesor_sign' => $signatures['asesor']['signature'],
            'asesor_reg' => $signatureData['reg_number'] ?? '',
            'asesor_qr_code' => $signatureData['qr_code'] ?? '',
            'asesi_sign_date' => $this->convertDateToLocalTime($signatures['asesi']['date'] ?? now()),
            'asesor_sign_date' => $this->convertDateToLocalTime($signatures['asesor']['date'] ?? now()),
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

        $pdf = Pdf::loadView('forms.AK.ak07', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-07_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}