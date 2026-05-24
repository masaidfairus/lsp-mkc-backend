<?php

namespace App\Http\Controllers\PDF;

use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PdfIA01Controller extends Controller
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

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.01')
            ->first();

                    // ── Validasi Tanda Tangan Asesor (Kecuali untuk Admin/Asesor Peninjau) ───
        $asesorSig = $this->getUserSignature($registration->assessor?->id, $registration->id, 'IA.01', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$asesorSig['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen IA.01 Belum Ditandatangani', 
                'Dokumen FR.IA.01 belum dapat dicetak karena Asesor belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.01 Belum Siap', 'Dokumen FR.IA.01 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $mainData    = $instrument->main_data ?? [];
        $header      = $mainData['header'] ?? [];
        $sections    = $mainData['sections'] ?? [];
        $rekomendasi = $mainData['rekomendasi'] ?? [];

        // Asesi signature (biasanya tidak wajib per dokumen/bisa ambil dari profil)
        $asesiSig  = $this->getUserSignature($registration->candidate?->id);

        // Transform data
        $kelompok_pekerjaan = [];
        $kpNo = 1;

        foreach ($sections as $section) {
            $units = [];
            foreach ($section['kuk_rows'] ?? [] as $row) {
                $unitKey = $row['unit_code'] ?? 'unknown';
                if (!isset($units[$unitKey])) {
                    $units[$unitKey] = ['kode' => $row['unit_code'] ?? '', 'judul' => $row['unit_title'] ?? '', 'elemen' => []];
                }
                $elKey = $row['elemen'] ?? '';
                if (!isset($units[$unitKey]['elemen'][$elKey])) {
                    $units[$unitKey]['elemen'][$elKey] = ['nama' => $elKey, 'kuk' => []];
                }
                $units[$unitKey]['elemen'][$elKey]['kuk'][] = [
                    'kuk' => $row['kuk'] ?? '',
                    'industry_standard' => $row['standar_industri'] ?? '',
                    'achievement_is_competent' => ($row['pencapaian'] ?? '') === 'ya',
                    'further_assessment_notes' => $row['penilaian_lanjut'] ?? '',
                ];
            }
            $units = array_values($units);
            foreach ($units as &$u) $u['elemen'] = array_values($u['elemen']);

            $kelompok_pekerjaan[] = ['no' => $kpNo++, 'nama' => $section['work_group'] ?? '', 'unit' => $units];
        }

        // Prefer the asesor chosen in the form (main_data.signatures)
        $signatures = $mainData['signatures'] ?? [];
        $asesorSigData = $signatures['asesor'] ?? $signatures['assessor'] ?? [];
        $asesiSigData = $signatures['asesi'] ?? []; // Fallback jika suatu saat ada di json

        // Resolve asesor signature & QR from form data first, then fall back to DB lookup
        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->registration_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['tanggal'] ?? now()->format('d-m-Y');

        $asesiSignature  = $asesiSigData['signature'] ?? $asesiSig['signature'] ?? null;
        $asesiDate       = $asesiSigData['date'] ?? $header['tanggal'] ?? now()->format('d-m-Y');

        $data = [
            'nama_skema'  => $header['scheme_name'] ?? '-',
            'kode_skema'  => $header['scheme_code'] ?? '-',
            'tuk'         => $header['tuk'] ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor' => $header['asesor_name'] ?? $registration->assessor?->name ?? '-',
            'nama_asesi'  => $header['asesi_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'     => $header['tanggal'] ?? now()->format('d-m-Y'),

            'kelompok_pekerjaan' => $kelompok_pekerjaan,

            'is_competent'         => ($rekomendasi['hasil'] ?? '') === 'kompeten',
            'not_competent_detail' => $rekomendasi['catatan_belum_kompeten'] ?? [],

            'asesi_name'       => $registration->candidate?->name ?? '',
            'asesi_date'       => $asesiDate,
            'asesi_signature'  => $asesiSignature,

            'asesor_name'      => $asesorName,
            'asesor_reg'       => $asesorReg,
            'asesor_date'      => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,
        ];

                // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $registration->id, 'IA.01');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $registration->id, 'IA.01');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $registration->id, 'IA.01');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $registration->id, 'IA.01');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.IA.ia01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_01_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }
}