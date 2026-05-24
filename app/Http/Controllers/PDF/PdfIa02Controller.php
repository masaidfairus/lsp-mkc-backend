<?php

namespace App\Http\Controllers\PDF;

use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PdfIa02Controller extends Controller
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
            ->where('document_type', 'FR.IA.02')
            ->first();

        if (!$instrument) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.02 Belum Siap', 'Dokumen FR.IA.02 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $instrument->load(['registration.candidate', 'registration.scheme', 'registration.assessor']);

        $mainData    = $instrument->main_data ?? [];
        $header      = $mainData['header'] ?? [];
        $tasks       = $mainData['demonstration_tasks'] ?? [];
        $docControl  = $mainData['document_control'] ?? [];

        // Signature lookup
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null, $instrument?->id ?? ($record->id ?? null), 'Asesor');
        $asesiSig = $this->getUserSignature($registration->candidate?->id ?? null);

        $signatures = $mainData['signatures'] ?? [];
        $asesorSigData = $signatures['assessor'] ?? [];
        $asesiSigData = $signatures['asesi'] ?? []; // Fallback jika suatu saat ada di json

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['assessment_date'] ?? now()->format('d-m-Y');

        $asesiSignature  = $asesiSigData['signature'] ?? $asesiSig['signature'] ?? null;
        $asesiName       = $registration->candidate?->name ?? '';
        $asesiDate       = $asesorDate; // Biasanya asesi ttd bareng asesor

        // Transform demonstration_tasks to kelompok_pekerjaan
        $kelompok_pekerjaan = [];
        $kpNo = 1;
        foreach ($tasks as $task) {
            $units = [];
            foreach ($task['units'] ?? [] as $u) {
                $units[] = [
                    'kode' => $u['unit_code'] ?? '',
                    'judul' => $u['unit_title'] ?? '',
                ];
            }
            $kelompok_pekerjaan[] = [
                'no' => $kpNo++,
                'nama' => $task['work_group'] ?? '',
                'unit' => $units,
                'scenario' => $task['scenario'] ?? '',
                'perlengkapan' => array_column($task['equipments'] ?? [], 'item'),
                'waktu' => $task['time_allocation'] ?? '',
            ];
        }

        $data = [
            'nama_skema'  => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema'  => $header['scheme_code'] ?? $registration->scheme?->code ?? '-',
            'tuk'         => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor' => $header['assessor_name'] ?? $registration->assessor?->name ?? '-',
            'nama_asesi'  => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'     => $header['assessment_date'] ?? now()->format('d-m-Y'),

            'kelompok_pekerjaan' => $kelompok_pekerjaan,

            'penyusun' => $docControl['compilers'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],
            'validator' => $docControl['validators'] ?? [['nama' => '', 'nomor_met' => ''], ['nama' => '', 'nomor_met' => '']],

            'asesor_name'      => $asesorName,
            'asesor_reg'       => $asesorReg,
            'asesor_date'      => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'asesi_name'       => $asesiName,
            'asesi_date'       => $asesiDate,
            'asesi_signature'  => $asesiSignature,
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

        $pdf = Pdf::loadView('forms.IA.ia02', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true);

        $filename = 'FR_IA_02_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Mengambil user signature.
     *
     * @param  mixed  $userId
     * @param  mixed  $modelId
     * @param  mixed  $role
     * @return mixed
     */
    public function getUserSignature($userId, $modelId = null, $role = null)
    {
        if (!$userId) return ['signature' => null, 'qr_code' => null];

        $query = \App\Models\SignatureRequest::where('user_id', $userId)->where('status', 'signed');
        if ($modelId) $query->where('signable_id', $modelId);
        if ($role) $query->where('role', $role);
        
        $req = $query->latest('signed_at')->first();
        if ($req) {
            return ['signature' => $req->raw_signature, 'qr_code' => $req->qr_code];
        }

        $sig = \Illuminate\Support\Facades\DB::table('user_signatures')->where('user_id', $userId)->latest('created_at')->first();
        return $sig ? ['signature' => $sig->signature_image ?? $sig->signature, 'qr_code' => $sig->qr_code] : ['signature' => null, 'qr_code' => null];
    }
}
