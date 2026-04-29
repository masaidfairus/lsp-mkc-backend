<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfIa05cController extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.05C')
            ->first();

        // If the instrument is not generated yet, fallback
        if (!$instrument || empty($instrument->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.05C Belum Siap', 'Dokumen FR.IA.05C belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['candidate', 'scheme', 'assessor']);

        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        $mainData = $instrument->main_data ?? [];
        $header   = $mainData['header'] ?? [];

        $docControl   = $mainData['document_control'] ?? [];

        $signatures = $mainData['signatures'] ?? [];
        $asesorSigData = $signatures['assessor'] ?? [];
        $asesiSigData = $signatures['asesi'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['assessment_date'] ?? $header['date'] ?? now()->format('d-m-Y');
        
        $asesiSignature  = $asesiSigData['signature'] ?? $asesiSig['signature'] ?? null;
        $asesiDate       = $asesiSigData['date'] ?? $header['assessment_date'] ?? $header['date'] ?? now()->format('d-m-Y');

        $answerSheet = $mainData['answer_sheet'] ?? [];
        $jawabanData = [];
        foreach ($answerSheet as $item) {
            $jawabanData[] = [
                'no' => $item['no'] ?? '',
                'jawaban' => $item['answer'] ?? '',
                'is_correct' => filter_var($item['achievement']['is_yes'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
                'unachieved_unit' => $item['unachieved_unit'] ?? '',
            ];
        }

        $feedback = $mainData['feedback_section'] ?? [];
        $knowledgeStatus = $feedback['knowledge_aspect_status'] ?? 'Tercapai';
        $unachievedDetails = $feedback['unachieved_details'] ?? '';

        $data = [
            'nama_skema'       => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema'       => $header['scheme_number'] ?? $header['scheme_code'] ?? $registration->scheme?->code ?? '-',
            'tuk'              => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor'      => $header['assessor_name'] ?? $asesorName,
            'nama_asesi'       => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'          => $header['assessment_date'] ?? $header['date'] ?? now()->format('d-m-Y'),
            'waktu'            => $header['time'] ?? '-',
            
            'asesi_signature'  => $asesiSignature,
            'asesi_date'       => $asesiDate,
            'asesor_reg'       => $asesorReg,
            'asesor_date'      => $asesorDate,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'jawaban_data'     => $jawabanData,
            'knowledge_status' => $knowledgeStatus,
            'unachieved_details' => $unachievedDetails,
            'umpan_balik'      => $feedback['assessee_feedback'] ?? '',
            'compilers'        => $docControl['compilers'] ?? [],
            'penyusun'         => $docControl['compilers'] ?? [],
            'validators'       => $docControl['validators'] ?? [],
            'validator'        => $docControl['validators'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.IA.ia05c', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_05C_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }

    private function getUserSignature($userId)
    {
        if (!$userId) return ['signature' => null, 'qr_code' => null];

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
