<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk06Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'reports'
        ]);

        $ak06Report = $registration->reports->where('document_type', 'FR.AK.06')->first();

        if (!$ak06Report || empty($ak06Report->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.06 Belum Siap', 'Dokumen FR.AK.06 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak06Data = $ak06Report->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        if (!$assessor) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen AK.06 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }

        $signatureData = $this->getAssessorSignatureData($assessor, $registration->id, 'AK.06', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$signatureData['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen AK.06 Belum Ditandatangani', 
                'Dokumen FR.AK.06 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        $assessorFooter = $ak06Data['assessor_footer'] ?? [];
        $assessorFooter['qr_preview'] = $signatureData['qr_code'];

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'principles' => $ak06Data['assessment_principles_review'] ?? [],
            'dimensions' => $ak06Data['competency_dimensions_review'] ?? [],
            'assessor_signature' => $assessorFooter,
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

        $pdf = Pdf::loadView('forms.AK.ak06', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-06_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
