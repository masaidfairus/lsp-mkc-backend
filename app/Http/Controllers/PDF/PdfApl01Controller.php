<?php

namespace App\Http\Controllers\PDF;

use App\Enums\Apl01Status;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PdfApl01Controller extends Controller
{
    /**
     * GET /api/apl01/{registration}
     *
     * Stream PDF APL-01 dari data real database.
     * Akses: kandidat pemilik registrasi ATAU admin/assessor.
     */
    public function show(Request $request, AssessmentRegistration $registration)
    {

       if ($registration->status_apl01 !== Apl01Status::APPROVED) {
        return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen APL.01 Belum Siap', 'Dokumen APL.01 belum dapat dicetak karena status APL.01 masih dalam proses persetujuan.');
    }

        // ── Otorisasi ────────────────────────────────────────────────
        $user = $request->user();

        if (!in_array($user->role, ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        // ── Validasi status ──────────────────────────────────────────
        if (is_null($registration->status_apl01)) {
            return response()->json(['message' => 'APL-01 belum disubmit.'], 422);
        }

        // ── Load semua relasi sekaligus (N+1 safe) ───────────────────
        $registration->load([
            'candidate',                     // users
            'aplData',                       // apl01_data       → Bagian 1
            'scheme',                        // schemes          → Bagian 2
            'scheme.competencyUnits',        // competency_units → Bagian 2 (unit list)
            'filteredAplUserFiles.documentList.basicRequirementProof',
            'filteredAplUserFiles.documentList.administrativeProof',          // apl01_evidence + scheme_requirements → Bagian 3
        ]);

        $aplData = $registration->aplData;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $files = $registration->filteredAplUserFiles;

        // ── Guard: data wajib harus ada ──────────────────────────────
        if (!$aplData || !$candidate || !$scheme || !$files) {
            return response()->json(['message' => 'Data registrasi tidak lengkap.'], 422);
        }

        // ── Bangun array $data untuk blade ───────────────────────────
        $data = [
            // ============================================================
            // BAGIAN 1 — Data Pribadi
            // ============================================================
            'nama' => strtoupper($candidate->name),
            'nik' => $candidate->identity_number ?? '-',
            'email' => $candidate->email,
            'tempat_lahir' => $aplData->place_of_birth ?? '-',  // ← fix

            'tanggal_lahir' => Carbon::parse($aplData->date_of_birth)->translatedFormat('d F Y'),
            'jenis_kelamin' => $aplData->gender === 'male' ? 'Laki-laki' : 'Perempuan',
            'no_telp' => $aplData->phone_number,
            'pendidikan' => $aplData->education_qualifications,

            'alamat' => $aplData->address,
            'rt_rw' => null,
            'kelurahan' => '',
            'kecamatan' => '',
            'kabupaten' => '',
            'provinsi' => '',
            'kode_pos' => $aplData->home_postal_code ?? '',  // ← fix
            'kebangsaan' => 'Indonesia',

            // ============================================================
            // BAGIAN 1 — Data Pekerjaan
            // ============================================================
            'instansi' => $aplData->company_name,
            'pekerjaan' => $aplData->job_title,
            'alamat_instansi' => $aplData->company_address,
            'kode_pos_kantor' => $aplData->company_postal_code ?? '',  // ← fix

            // ============================================================
            // BAGIAN 2 — Skema Sertifikasi
            // ============================================================
            'nama_skema' => $scheme->name,
            'kode_skema' => $scheme->code,

            'tujuan_asesmen' => $registration->certification_purpose instanceof \BackedEnum
                ? $registration->certification_purpose->value
                : ($registration->certification_purpose ?? ''),

            'unit_kompetensi' => $scheme->competencyUnits
                ->map(fn($unit) => [
                    'kode' => $unit->unit_code,
                    'judul' => $unit->unit_title,
                ])
                ->toArray(),

            // ============================================================
            // BAGIAN 3 — Bukti Kelengkapan
            // ============================================================

            'persyaratan_dasar' => $files->filter(function ($file) use ($scheme) {
                return $file->documentList?->basicRequirementProof()
                    ->where('scheme_id', $scheme->id)
                    ->exists();
            })->map(fn($file) => [
                    'nama' => $file->documentList->title,
                    'status' => $file->status, // Mengasumsikan ini adalah Enum
                ])->values()->toArray(),

            // Bagian 3.2: Bukti Administratif
            'bukti_administratif' => $files->filter(function ($file) {
                return $file->documentList?->administrativeProof()->exists();
            })->map(fn($file) => [
                    'nama' => $file->documentList->title,
                    'status' => $file->status,
                ])->values()->toArray(),

            'status_terima' => match (true) {
                $registration->status_apl01 === Apl01Status::APPROVED => 'DITERIMA',
                $registration->status_apl01 === Apl01Status::REJECTED => 'DITOLAK',
                default => 'DIPROSES',
            },
            'asesor_reg' => $this->getAssessorSignatureData($registration->assessor)['reg_number'],
            'asesor_qr_code' => $this->getAssessorSignatureData($registration->assessor)['qr_code'],
            'asesor_name' => $registration->assessor?->name ?? '',
            'asesor_sign_date' => $registration->status_apl01 === Apl01Status::APPROVED ? $this->convertDateToLocalTime($registration->updated_at) : '',
        ];

        // ── Render & stream PDF ──────────────────────────────────────
        $pdf = Pdf::loadView('forms.APL.apl01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'APL-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}