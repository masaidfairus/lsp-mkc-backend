<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfAk04Controller extends Controller
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
            'appeals'
        ]);

        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $assessor = $registration->assessor;

        $signatureData = $this->getAssessorSignatureData($assessor, $registration->id, 'AK.04', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$signatureData['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen AK.04 Belum Ditandatangani', 
                'Dokumen FR.AK.04 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }
        $appeal = $registration->appeals->first();
        $ak04Data = $appeal?->ak04_data;

        if (!$ak04Data) {
            return view('components.status-page', [
                'type' => 'danger',
                'title' => 'Data Banding Tidak Ditemukan',
                'message' => 'Belum ada pengajuan banding (AK.04) untuk pendaftaran ini.',
                'details' => ['Dokumen AK.04 hanya tersedia jika asesi telah mengajukan banding melalui portal asesi.'],
                'button_text' => 'Kembali',
                'button_url' => url()->previous(),
            ]);
        }

        $rawAssessmentDate = $ak04Data['assessment_date'] ?? null;
        if (!$rawAssessmentDate) {
            return view('components.status-page', [
                'type' => 'danger',
                'title' => 'Data AK.04 Tidak Lengkap',
                'message' => 'Beberapa informasi penting dalam dokumen AK.04 tidak ditemukan.',
                'details' => ['Kemungkinan data terhapus atau format tidak sesuai. Silakan hubungi admin.'],
                'button_text' => 'Kembali',
                'button_url' => url()->previous(),
            ]);
        }

        $assessmentDate = $this->convertDateToLocalTime($rawAssessmentDate);
        $notes = $ak04Data['reason_notes'] ?? '-';

        $asesiSign = $ak04Data['signature'] ?? ['asesi_signature' => null, 'date' => null];
        $appealQuestions = collect($ak04Data['appeal_questions'] ?? [])->pluck('answer', 'id')->toArray();
      
        $data = [
            'scheme_name' => $scheme?->name ?? '-',
            'scheme_code' => $scheme?->code ?? '-',
            'asesi_name' => strtoupper($candidate?->name ?? 'Unknown'),
            'asesor_name' => $assessor?->name ?? 'Belum Ditugaskan',
            'assessment_date' => $assessmentDate,
            'appeal_questions' => $appealQuestions,
            'reason_notes' => $notes,
            'asesi_sign' => $asesiSign['asesi_signature'] ?? null,
            'asesi_qr_code' => $appeal->asesi_qr_code,
            'asesi_sign_date' => $asesiSign['date'] ? $this->convertDateToLocalTime($asesiSign['date']) : '-',
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

        $pdf = Pdf::loadView('forms.ak.ak04', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        $filename = 'AK-04_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}