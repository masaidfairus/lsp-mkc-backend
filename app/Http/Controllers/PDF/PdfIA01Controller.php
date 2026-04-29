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
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.01')
            ->first();

                    // ==================== DEBUGGER QR CODE & SIGNATURE ====================
        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        \Log::info('=== PDF IA.01 DEBUG SIGNATURE ===', [
            'asesi_id'         => $registration->candidate?->id,
            'asesor_id'        => $registration->assessor?->id,
            'asesi_signature'  => $asesiSig['signature'] ? 'ADA (' . strlen($asesiSig['signature']) . ' chars)' : 'KOSONG',
            'asesor_qr_code'   => $asesorSig['qr_code']   ? 'ADA (' . strlen($asesorSig['qr_code']) . ' chars)' : 'KOSONG',
            'asesor_signature' => $asesorSig['signature'] ? 'ADA' : 'KOSONG',
        ]);
        // =====================================================================

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.01 Belum Siap', 'Dokumen FR.IA.01 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $mainData    = $instrument->main_data ?? [];
        $header      = $mainData['header'] ?? [];
        $sections    = $mainData['sections'] ?? [];
        $rekomendasi = $mainData['rekomendasi'] ?? [];

        // === DEBUGGER SIGNATURE ===
        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        // Debug output (bisa dilihat di terminal/storage/logs)
        \Log::info('=== PDF IA.01 DEBUG ===', [
            'asesi_id'           => $registration->candidate?->id,
            'asesor_id'          => $registration->assessor?->id,
            'asesi_signature'    => $asesiSig['signature'] ? 'ADA (' . strlen($asesiSig['signature']) . ' chars)' : 'TIDAK ADA',
            'asesor_qr_code'     => $asesorSig['qr_code'] ? 'ADA (' . strlen($asesorSig['qr_code']) . ' chars)' : 'TIDAK ADA',
            'asesor_signature'   => $asesorSig['signature'] ? 'ADA' : 'TIDAK ADA',
        ]);

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
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
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

        private function getUserSignature($userId)
    {
        if (!$userId) {
            return ['signature' => null, 'qr_code' => null];
        }

        $sig = DB::table('user_signatures')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->first();

        return $sig ? [
            'signature' => $sig->signature,
            'qr_code'   => $sig->qr_code,
        ] : ['signature' => null, 'qr_code' => null];
    }

}