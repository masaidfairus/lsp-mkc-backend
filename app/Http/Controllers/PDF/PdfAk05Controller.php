<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAk05Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'assessor',
            'scheme',
            'reports'
        ]);

        $ak05Report = $registration->reports->where('document_type', 'FR.AK.05')->first();

        if (!$ak05Report || empty($ak05Report->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen AK.05 Belum Siap', 'Dokumen FR.AK.05 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $ak05Data = $ak05Report->main_data;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        if (!$assessor) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen AK.05 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }

        $signatureData = $this->getAssessorSignatureData($assessor, $registration->id, 'AK.05', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$signatureData['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen AK.05 Belum Ditandatangani', 
                'Dokumen FR.AK.05 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        $assessorFooter = $ak05Data['assessor_footer'] ?? [];
        $assessorSignature = [
            'notes' => $assessorFooter['notes'] ?? '',
            'reg_number' => $signatureData['reg_number'],
            'date' => $assessorFooter['assessor_details']['date'] ?? '',
            'qr_preview' => $signatureData['qr_code'],
        ];

        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'tuk' => $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'asesi_name' => strtoupper($candidate?->name ?? '-'),
            'asesor_name' => $assessor?->name ?? '-',
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'assessment_results' => $ak05Data['assessment_results'] ?? [],
            'evaluations' => $ak05Data['evaluation_section'] ?? [],
            'assessor_signature' => $assessorSignature,
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

        $pdf = Pdf::loadView('forms.ak.ak05', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-05_' . str_replace(' ', '_', $candidate?->name ?? 'Asesi') . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
