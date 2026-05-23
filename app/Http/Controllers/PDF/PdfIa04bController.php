<?php

namespace App\Http\Controllers\PDF;

use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PdfIa04bController extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);

        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.04B')
            ->first();

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.04B Belum Siap', 'Dokumen FR.IA.04B belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $dataIa = $instrument->main_data ?? [];
        $header = $dataIa['header'] ?? [];
        $aspects = $dataIa['assessment_aspects'] ?? [];
        $docControl = $dataIa['document_control'] ?? [];

        // Signature lookup
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null, $instrument?->id ?? ($record->id ?? null), 'Asesor');
        $asesiSig = $this->getUserSignature($registration->candidate?->id ?? null);

        $signatures = $dataIa['signatures'] ?? [];
        $asesorSigData = $signatures['assessor'] ?? [];
        $asesiSigData = $signatures['asesi'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode = $asesorSigData['qr_code'] ?? $asesorSig['qr_code'] ?? null;
        $asesorName = $asesorSigData['name'] ?? $registration->assessor?->name ?? '';
        $asesorReg = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate = $asesorSigData['date'] ?? $header['date'] ?? now()->format('d-m-Y');

        $asesiSignature = $asesiSigData['signature'] ?? $asesiSig['signature'] ?? null;
        $asesiDate = $asesiSigData['date'] ?? $header['date'] ?? now()->format('d-m-Y');

        $questionsRaw = $dataIa['questions'] ?? [];
        $complianceRaw = $dataIa['competency_standard_compliance'] ?? [];

        $questions = [];
        foreach ($questionsRaw as $idx => $q) {
            $questions[] = ($idx + 1) . '. ' . trim($q['text'] ?? '');
        }

        $compliance = [];
        foreach ($complianceRaw as $idx => $c) {
            $compliance[] = ($idx + 1) . '. ' . trim($c['text'] ?? '');
        }

        $fullPertanyaan = implode("\n", $questions);
        $fullKesesuaian = implode("\n", $compliance);

        $aspek_penilaian = [];
        // We still need rows for Lingkup and Pencapaian
        // If aspects is empty, we should show at least some rows for the questions to be visible
        $rowCount = max(count($aspects), 1);

        for ($i = 0; $i < $rowCount; $i++) {
            $aspect = $aspects[$i] ?? null;

            $aspek_penilaian[] = [
                'lingkup' => $aspect['presentation_scope'] ?? '',
                'achievement' => $aspect['achievement'] ?? null,
            ];
        }

        $data = [
            'judul' => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'nomor' => $header['scheme_number'] ?? $registration->scheme?->code ?? '-',
            'tuk' => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor' => $header['assessor_name'] ?? $registration->assessor?->name ?? '-',
            'nama_asesi' => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal' => $header['date'] ?? now()->format('d-m-Y'),

            'aspek_penilaian' => $aspek_penilaian,
            'full_pertanyaan' => $fullPertanyaan,
            'full_kesesuaian' => $fullKesesuaian,
            'rekomendasi' => $dataIa['assessor_recommendation'] ?? null,

            'penyusun' => $docControl['compilers'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],
            'validator' => $docControl['validators'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],

            'asesi_nama' => $registration->candidate?->name ?? '',
            'asesi_ttd_tanggal' => $asesiDate,
            'asesi_signature' => $asesiSignature,

            'asesor_nama' => $asesorName,
            'asesor_no_reg' => $asesorReg,
            'asesor_ttd_tanggal' => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code' => $asesorQrCode,
        ];

        // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code'] = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code'] = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code'] = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code'] = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.ia.ia04b', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true);

        $filename = 'FR_IA_04B_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }

    public function getUserSignature($userId, $modelId = null, $role = null)
    {
        if (!$userId)
            return ['signature' => null, 'qr_code' => null];

        $query = \App\Models\SignatureRequest::where('user_id', $userId)->where('status', 'signed');
        if ($modelId)
            $query->where('signable_id', $modelId);
        if ($role)
            $query->where('role', $role);

        $req = $query->latest('signed_at')->first();
        if ($req) {
            return ['signature' => $req->raw_signature, 'qr_code' => $req->qr_code];
        }

        $sig = \Illuminate\Support\Facades\DB::table('user_signatures')->where('user_id', $userId)->latest('created_at')->first();
        return $sig ? ['signature' => $sig->signature_image ?? $sig->signature, 'qr_code' => $sig->qr_code] : ['signature' => null, 'qr_code' => null];
    }
}
