<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\AssessmentStatus;

class PdfApl02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'scheme.competencyUnits.competencyElements',
            'selfAssessments',
            'aplPortfolio.fileProfile.documentList'
        ]);

        $candidate = $registration->candidate;
        $userId = $candidate->id;
        $scheme = $registration->scheme;
        $schemeId = $scheme->id;
        $assessor = $registration->assessor;

        // ── Validasi status APL.02 (Menggunakan Enum baru) ───────────
        if ($registration->status !== AssessmentStatus::APL02_SUBMITTED && !$registration->status->isApl02Complete()) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration,
                'Dokumen APL.02 Belum Siap',
                'Dokumen FR.APL.02 belum dapat dicetak karena status APL.02 masih dalam proses pengisian atau belum disubmit oleh Asesi.'
            );
        }

        if (!$assessor) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen APL.02 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }
        $selfAssessments = collect($registration->selfAssessments)->keyBy('element_id');

        $portfolios = $registration->aplPortfolio->map(function ($p) {
            return $p->fileProfile->documentList->title ?? 'Dokumen Tanpa Nama';
        })->unique();

        // ── Validasi Tanda Tangan Asesor (Kecuali untuk Admin/Asesor Peninjau) ───
        $asesorSig = $this->getUserSignature($registration->assessor?->id, $registration->id, 'APL.02', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$asesorSig['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration,
                'Dokumen APL.02 Belum Ditandatangani',
                'Dokumen FR.APL.02 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        $data = [
            'nama_skema' => $scheme->name,
            'kode_skema' => $scheme->code,
            'nama_asesi' => strtoupper($candidate->name),
            'nama_asesor' => $assessor?->name ?? 'Belum Ditugaskan',
            'no_reg_asesor' => $asesorSig['reg_number'] ?? '',
            'asesor_qr_code' => $asesorSig['qr_code'],
            'asesor_date' => $asesorSig['is_signed'] ? $this->convertDateToLocalTime($asesorSig['signed_at'] ?? $registration->updated_at) : '',
            'asesi_qr_code' => $registration->apl02_qr_code ?? '',
            'asesi_date' => $registration->apl02_signature_date ? $this->convertDateToLocalTime($registration->apl02_signature_date) : '',
            'unit_kompetensi' => $scheme->competencyUnits ?? [],
            'asesmen_mandiri' => $selfAssessments ?? [],
            'portfolios' => $portfolios,
            'is_apl02_continuable' => $registration->is_apl02_continuable,
            'apl02_approach' => $registration->apl02_approach,
        ];



        $pdf = Pdf::loadView('forms.apl.apl02', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'APL-02_' . str_replace(' ', '_', $data['nama_asesi']) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}