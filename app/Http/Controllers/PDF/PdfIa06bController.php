<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfIa06bController extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);

        if (!$isAuthorized) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.06B')
            ->first();

        // If the instrument is not generated yet, fallback
        if (!$instrument || empty($instrument->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.06B Belum Siap', 'Dokumen FR.IA.06B belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['candidate', 'scheme', 'assessor']);

        $asesiSig = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null, $instrument?->id ?? ($record->id ?? null), 'Asesor');

        $mainData = $instrument->main_data ?? [];
        $header = $mainData['header'] ?? [];

        $docControl = $mainData['document_control'] ?? [];
        $asesorSigData = $mainData['signatures']['assessor'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode = $asesorSigData['qr_code'] ?? $asesorSig['qr_code'] ?? null;
        $asesorName = $asesorSigData['name'] ?? $registration->assessor?->name ?? '';
        $asesorReg = $asesorSigData['reg_number'] ?? $registration->assessor?->registration_number ?? '';
        $asesorDate = $asesorSigData['date'] ?? $header['assessment_date'] ?? $header['date'] ?? now()->format('d-m-Y');

        $data = [
            'nama_skema' => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema' => $header['scheme_number'] ?? $header['scheme_code'] ?? $registration->scheme?->code ?? '-',
            'tuk' => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor' => $header['assessor_name'] ?? $asesorName,
            'nama_asesi' => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal' => $header['assessment_date'] ?? $header['date'] ?? now()->format('d-m-Y'),

            'asesi_signature' => $asesiSig['signature'] ?? null,
            'asesor_reg' => $asesorReg,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code' => $asesorQrCode,

            'compilers' => $docControl['compilers'] ?? [],
            'penyusun' => $docControl['compilers'] ?? [],
            'validators' => $docControl['validators'] ?? [],
            'validator' => $docControl['validators'] ?? [],
            'kunci_jawaban' => array_map(fn($item) => ['jawaban' => $item['key_answer_text'] ?? ''], $mainData['answer_keys'] ?? []),
            'waktu' => $header['assessment_time'] ?? $header['time'] ?? '-',
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

        $pdf = Pdf::loadView('forms.ia.ia06b', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_06B_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

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
