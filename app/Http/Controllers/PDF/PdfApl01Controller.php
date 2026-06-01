<?php

namespace App\Http\Controllers\PDF;

use App\Enums\Apl01Status;
use App\Enums\AssessmentStatus;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PdfApl01Controller extends Controller
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
        // ── Validasi status APL.01 (Menggunakan Enum baru) ───────────
        if (!$registration->status->isApl01Complete()) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen APL.01 Belum Siap', 
                'Dokumen FR.APL.01 belum dapat dicetak karena status APL.01 masih dalam proses persetujuan oleh Admin.'
            );
        }

        // ── Validasi Tanda Tangan Admin (Kecuali untuk Admin/Asesor Peninjau) ───
        $sigReq = \App\Models\SignatureRequest::where('registration_id', $registration->id)
            ->where('document_label', 'APL.01')
            ->where('status', 'signed')
            ->first();

        if (!$sigReq) {
            $sigReq = \App\Models\SignatureRequest::where('registration_id', $registration->id)
                ->where('document_label', 'APL.01')
                ->first();
        }

        $adminUser = $sigReq ? $sigReq->user : null;
        if (!$adminUser) {
            $adminUser = auth()->user();
            if (!$adminUser || !in_array($adminUser->role, ['admin', 'superadmin'])) {
                $adminUser = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->first();
            }
        }

        $signatureData = $this->getAssessorSignatureData($adminUser, $registration->id, 'APL.01', true);
        $isAuthorizedReviewer = $this->isAuthorizedReviewer($request->user(), $registration);

        if (!$signatureData['is_signed'] && !$isAuthorizedReviewer) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show(
                $registration, 
                'Dokumen APL.01 Belum Ditandatangani', 
                'Dokumen FR.APL.01 belum dapat dicetak karena Admin belum menandatangani dokumen ini di menu Inbox Tanda Tangan.'
            );
        }

        // ── Otorisasi ────────────────────────────────────────────────
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        // ── Validasi status ──────────────────────────────────────────
        if ($registration->status === AssessmentStatus::APL01_DRAFT) {
            return response()->json(['message' => 'APL-01 belum disubmit.'], 422);
        }

        // ── Load semua relasi sekaligus (N+1 safe) ───────────────────
        $registration->load([
            'candidate.profile',
            'aplData.education',
            'scheme',
            'scheme.competencyUnits',
            'filteredAplUserFiles.documentList.basicRequirementProofs',
            'filteredAplUserFiles.documentList.administrativeProofs',
            'aplPortfolio.fileProfile.documentList',
        ]);

        $aplData = $registration->aplData;
        $candidate = $registration->candidate;
        $scheme = $registration->scheme;
        $files = $registration->filteredAplUserFiles;

        if (!$aplData || !$candidate || !$scheme || !$files) {
            return response()->json(['message' => 'Data registrasi tidak lengkap.'], 422);
        }

        $data = [
            'nama' => strtoupper($candidate->name),
            'nik' => $candidate->identity_number ?? ($candidate->profile?->identity_number ?? '-'),
            'email' => $candidate->email,
            'tempat_lahir' => $aplData->place_of_birth ?? '-',
            'tanggal_lahir' => $aplData->date_of_birth ? Carbon::parse($aplData->date_of_birth)->translatedFormat('d F Y') : '-',
            'jenis_kelamin' => $aplData->gender && $aplData->gender->value === 'male' ? 'Laki-laki' : 'Perempuan',
            'no_telp' => $aplData->phone_number,
            'pendidikan' => $aplData->education?->title ?? '-',
            'alamat' => $aplData->address,
            'rt_rw' => null, // Belum ada di skema database apl01_data
            'kelurahan' => '',
            'kecamatan' => '',
            'kabupaten' => '',
            'provinsi' => '',
            'kode_pos' => $aplData->home_postal_code ?? '',
            'kebangsaan' => 'Indonesia',
            'instansi' => $aplData->company_name,
            'pekerjaan' => $aplData->job_title,
            'alamat_instansi' => $aplData->company_address,
            'kode_pos_kantor' => $aplData->company_postal_code ?? '',
            'telp_kantor' => $aplData->company_contact ?? '',
            'fax_kantor' => $aplData->company_fax ?? '',
            'email_kantor' => $aplData->company_email ?? $candidate->email,
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
            'persyaratan_dasar' => $files->filter(function ($file) use ($scheme) {
                return $file->documentList && $file->documentList->basicRequirementProofs
                    ->where('scheme_id', $scheme->id)
                    ->isNotEmpty();
            })->map(fn($file) => [
                    'nama' => $file->documentList->title,
                    'status' => $file->status,
                    'validation_result' => $file->validation_result,
                ])->values()->toArray(),
            'bukti_administratif' => $files->filter(function ($file) {
                return $file->documentList && $file->documentList->administrativeProofs->isNotEmpty();
            })->map(fn($file) => [
                    'nama' => $file->documentList->title,
                    'status' => $file->status,
                    'validation_result' => $file->validation_result,
                ])->values()->toArray(),
            'admin_notes' => $registration->admin_notes ?? '',
            'status_terima' => match (true) {
                $registration->status === AssessmentStatus::APL01_APPROVED,
                $registration->status->isApl01Complete() => 'DITERIMA',
                $registration->status === AssessmentStatus::APL01_REJECTED => 'DITOLAK',
                default => 'DIPROSES',
            },
            'asesor_reg' => $signatureData['reg_number'],
            'asesor_qr_code' => $signatureData['qr_code'],
            'asesor_name' => $signatureData['name'],
            'asesor_sign_date' => $signatureData['is_signed'] && $signatureData['signed_at'] ? $this->convertDateToLocalTime($signatureData['signed_at']) : ($registration->status->isApl01Complete() ? $this->convertDateToLocalTime($registration->updated_at) : ''),
            'asesi_qr_code' => $aplData->asesi_qr_code ?? '',
            'asesi_sign_date' => $aplData->asesi_signature_date ? $this->convertDateToLocalTime($aplData->asesi_signature_date) : '',
            // ── Portfolio Dokumen Asesi ────────────────────────────────────────
            'portfolio_documents' => $registration->aplPortfolio->map(fn($p) => [
                'title' => $p->fileProfile->documentList->title ?? 'Dokumen Tanpa Nama',
                'is_uploaded' => true,
                'exists' => true,
            ])->unique('title')->values()->toArray(),
        ];


        $pdf = Pdf::loadView('forms.apl.apl01', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'APL-01_' . str_replace(' ', '_', $candidate->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}