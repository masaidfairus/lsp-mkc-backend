<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk01Controller extends Controller
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
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen AK.01 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }
        $ak01Data = $registration->plannings->pluck('ak01_data')->first();

        if (!$ak01Data) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.01 Belum Siap', 'Data dokumen AK.01 belum diisi. Silakan lengkapi terlebih dahulu melalui menu yang sesuai.');
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
        // ── Validasi Tanda Tangan Asesor (Kecuali untuk Admin/Asesor Peninjau) ───
        $asesorSig = $this->getUserSignature($registration->assessor?->id, $registration->id, 'AK.01', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$asesorSig['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen AK.01 Belum Ditandatangani', 
                'Dokumen FR.AK.01 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

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

        $pdf = Pdf::loadView('forms.AK.ak01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}