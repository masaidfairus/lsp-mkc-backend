<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfIa10Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.10')
            ->first();

        // If the instrument is not generated yet, fallback
        if (!$instrument || empty($instrument->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.10 Belum Siap', 'Dokumen FR.IA.10 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['candidate', 'scheme', 'assessor']);

        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        $mainData = $instrument->main_data ?? [];
        $header   = $mainData['header'] ?? [];
        $workGroups = $mainData['work_groups'] ?? [];
        $thirdParty = $mainData['third_party_details'] ?? [];
        $verification = $mainData['verification_questions'] ?? [];
        $narrative = $mainData['narrative_assessment'] ?? [];
        $conclusion = $mainData['overall_conclusion'] ?? [];
        
        $docControl = $mainData['document_control'] ?? [];
        $asesorSigData = $mainData['signatures']['assessor'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['date'] ?? now()->format('d-m-Y');

        $formattedVerification = [];
        foreach ($verification as $q) {
            $formattedVerification[] = [
                'question' => $q['question'] ?? '',
                'is_yes' => filter_var($q['is_yes'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
                'is_no' => filter_var($q['is_no'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
            ];
        }

        $data = [
            'nama_skema'       => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema'       => $header['scheme_number'] ?? $registration->scheme?->code ?? '-',
            'tuk'              => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor'      => $header['assessor_name'] ?? $asesorName,
            'nama_asesi'       => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'          => $header['date'] ?? now()->format('d-m-Y'),
            
            'asesi_signature'  => $asesiSig['signature'] ?? null,
            'asesor_reg'       => $asesorReg,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'work_groups' => $workGroups,
            'third_party' => $thirdParty,
            'verification' => $formattedVerification,
            'narrative' => $narrative,
            'conclusion' => $conclusion,

            'compilers' => $docControl['compilers'] ?? [],
            'validators' => $docControl['validators'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.IA.ia10', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_10_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

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
