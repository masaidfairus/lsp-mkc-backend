<?php

namespace App\Http\Controllers;

use App\Enums\Apl01Status;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class APL01Controller extends Controller
{
    /**
     * GET /api/apl01/{registration}
     *
     * Stream PDF APL-01 dari data real database.
     * Akses: kandidat pemilik registrasi ATAU admin/assessor.
     */
    public function show(Request $request, AssessmentRegistration $registration)
    {
        // ── Otorisasi ────────────────────────────────────────────────
        $user = $request->user();

        if (
            $user->id !== $registration->user_id &&
            !in_array($user->role, ['admin', 'assessor']) // sesuaikan dengan value Role enum lo
        ) {
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
            'evidence.requirement',          // apl01_evidence + scheme_requirements → Bagian 3
        ]);

        $aplData   = $registration->aplData;
        $candidate = $registration->candidate;
        $scheme    = $registration->scheme;

        // ── Guard: data wajib harus ada ──────────────────────────────
        if (!$aplData || !$candidate || !$scheme) {
            return response()->json(['message' => 'Data registrasi tidak lengkap.'], 422);
        }

        // ── Bangun array $data untuk blade ───────────────────────────
// ── Bangun array $data untuk blade ───────────────────────────
$data = [

    // ============================================================
    // BAGIAN 1 — Data Pribadi
    // ============================================================
    'nama'          => strtoupper($candidate->name),
    'nik'           => $candidate->identity_number ?? '-',
    'email'         => $candidate->email,
    'tempat_lahir'  => $aplData->place_of_birth ?? '-',  // ← fix

    'tanggal_lahir' => Carbon::parse($aplData->date_of_birth)->translatedFormat('d F Y'),
    'jenis_kelamin' => $aplData->gender === 'male' ? 'Laki-laki' : 'Perempuan',
    'no_telp'       => $aplData->phone_number,
    'pendidikan'    => $aplData->education_qualifications,

    'alamat'        => $aplData->address,
    'rt_rw'         => null,
    'kelurahan'     => '',
    'kecamatan'     => '',
    'kabupaten'     => '',
    'provinsi'      => '',
    'kode_pos'      => $aplData->home_postal_code ?? '',  // ← fix
    'kebangsaan'    => 'Indonesia',

    // ============================================================
    // BAGIAN 1 — Data Pekerjaan
    // ============================================================
    'instansi'        => $aplData->company_name,
    'pekerjaan'       => $aplData->job_title,
    'alamat_instansi' => $aplData->company_address,
    'kode_pos_kantor' => $aplData->company_postal_code ?? '',  // ← fix

    // ============================================================
    // BAGIAN 2 — Skema Sertifikasi
    // ============================================================
    'nama_skema'     => $scheme->name,
    'kode_skema'     => $scheme->code,

    'tujuan_asesmen' => $registration->certification_purpose instanceof \BackedEnum
        ? $registration->certification_purpose->value
        : ($registration->certification_purpose ?? ''),

    'unit_kompetensi' => $scheme->competencyUnits
        ->map(fn($unit) => [
            'kode'  => $unit->unit_code,
            'judul' => $unit->unit_title,
        ])
        ->toArray(),

    // ============================================================
    // BAGIAN 3 — Bukti Kelengkapan
    // ============================================================
    'persyaratan_dasar'   => $this->getRequirementNames($registration, mandatory: true),
    'bukti_administratif' => $this->getRequirementNames($registration, mandatory: false),

    'status_terima' => match (true) {
        $registration->status_apl01 === Apl01Status::APPROVED => 'DITERIMA',
        $registration->status_apl01 === Apl01Status::REJECTED  => 'DITOLAK',
        default                                                 => 'DIPROSES',
    },
];

        // ── Render & stream PDF ──────────────────────────────────────
        $pdf = Pdf::loadView('forms.APL.apl01', $data)
                  ->setPaper('a4', 'portrait');

        $filename = 'APL-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    // ────────────────────────────────────────────────────────────────
    // Helper
    // ────────────────────────────────────────────────────────────────

    /**
     * Ambil daftar nama requirement dari evidence yang sudah diupload kandidat,
     * difilter berdasarkan kolom `is_mandatory` di tabel scheme_requirements.
     *
     * @return array<string>  e.g. ['KTP', 'Pas Foto 3x4 Background Merah']
     */
    private function getRequirementNames(
        AssessmentRegistration $registration,
        bool $mandatory
    ): array {
        return $registration->evidence
            ->filter(fn($e) => $e->requirement?->is_mandatory === $mandatory)
            ->map(fn($e) => [
                'nama'   => $e->requirement->requirement_name,
                'status' => $e->status, // EvidenceStatus enum
            ])
            ->unique('nama')
            ->values()
            ->toArray();
    }
}