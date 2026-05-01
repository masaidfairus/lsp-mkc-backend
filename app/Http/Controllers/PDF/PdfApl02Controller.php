<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\Apl02Status;

class PdfApl02Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $registration->load([
            'candidate',
            'scheme.competencyUnits.competencyElements',
            'selfAssessments',
            'portfolioFiles'
        ]);

        $candidate = $registration->candidate;
        $userId = $candidate->id;
        $scheme = $registration->scheme;
        $schemeId = $scheme->id;
        $assessor = $registration->assessor;

        if (!$assessor) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Asesor Belum Ditugaskan', 'Dokumen APL.02 belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');
        }
        $selfAssessments = collect($registration->selfAssessments)->keyBy('element_id');

        // Ambil file secara manual lewat Model FileProfile agar filter lebih transparan
        $portfolios = \App\Models\FileProfile::where('user_id', $userId)
            ->whereHas('documentList.portfolioScheme', function ($q) use ($schemeId) {
                $q->where('scheme_id', $schemeId);
            })
            ->get();

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
            'unit_kompetensi' => $scheme->competencyUnits ?? [],
            'asesmen_mandiri' => $selfAssessments ?? [],
            'portfolios' => $portfolios,
        ];



        $pdf = Pdf::loadView('forms.APL.apl02', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'APL-02_' . str_replace(' ', '_', $data['nama_asesi']) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}