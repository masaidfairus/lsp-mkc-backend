<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfIa11Controller extends Controller
{
    public function show(Request $request, AssessmentRegistration $registration)
    {
        $user = $request->user();
        if (!in_array($user?->role ?? '', ['admin', 'asesor'])) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $instrument = AssessmentInstrument::where('registration_id', $registration->id)
            ->where('document_type', 'FR.IA.11')
            ->first();

        // If the instrument is not generated yet, fallback
        if (!$instrument || empty($instrument->main_data)) {
            return app(\App\Http\Controllers\PdfFallbackController::class)->show($registration, 'Dokumen IA.11 Belum Siap', 'Dokumen FR.IA.11 belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.');
        }

        $registration->load(['candidate', 'scheme', 'assessor']);

        $asesiSig  = $this->getUserSignature($registration->candidate?->id ?? null);
        $asesorSig = $this->getUserSignature($registration->assessor?->id ?? null);

        $mainData = $instrument->main_data ?? [];
        $header   = $mainData['header'] ?? [];
        $productDetails = $mainData['product_details'] ?? [];
        
        if (!empty($productDetails['product_image_url'])) {
            if (!str_starts_with($productDetails['product_image_url'], 'http') && !str_starts_with($productDetails['product_image_url'], 'data:')) {
                // Ensure it's a full path for DomPDF if needed, but Storage::url() usually works if remote is enabled
                $productDetails['product_image_url'] = public_path(str_replace('/storage/', 'storage/', \Illuminate\Support\Facades\Storage::url($productDetails['product_image_url'])));
                // Actually, public_path is better for DomPDF if it's local
                if (!file_exists($productDetails['product_image_url'])) {
                    // Fallback to URL if file doesn't exist at public_path
                    $productDetails['product_image_url'] = \Illuminate\Support\Facades\Storage::url($mainData['product_details']['product_image_url']);
                }
            }
        }

        $productReviews = $mainData['product_reviews'] ?? [];
        $recommendation = $mainData['assessor_recommendation'] ?? [];
        
        $docControl = $mainData['document_control'] ?? [];
        $asesorSigData = $mainData['signatures']['assessor'] ?? [];

        $asesorSignature = $asesorSigData['signature'] ?? $asesorSig['signature'] ?? null;
        $asesorQrCode    = $asesorSigData['qr_code']   ?? $asesorSig['qr_code']   ?? null;
        $asesorName      = $asesorSigData['name']       ?? $registration->assessor?->name ?? '';
        $asesorReg       = $asesorSigData['reg_number'] ?? $registration->assessor?->reg_number ?? '';
        $asesorDate      = $asesorSigData['date']       ?? $header['assessment_date'] ?? now()->format('d-m-Y');

        $formattedReviews = [];
        foreach ($productReviews as $category) {
            $criteria = [];
            foreach ($category['review_criteria'] ?? [] as $c) {
                $val = $c['achievement']['is_yes'] ?? null;
                $criteria[] = [
                    'criterion' => $c['criterion_text'] ?? '',
                    'is_yes' => filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
                    'is_no' => filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false,
                ];
            }
            $formattedReviews[] = [
                'category' => $category['category_name'] ?? '',
                'criteria' => $criteria,
            ];
        }

        $data = [
            'nama_skema'       => $header['certification_scheme'] ?? $registration->scheme?->name ?? '-',
            'kode_skema'       => $header['scheme_number'] ?? $registration->scheme?->code ?? '-',
            'tuk'              => $header['tuk'] ?? $registration->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri',
            'nama_asesor'      => $header['assessor_name'] ?? $asesorName,
            'nama_asesi'       => $header['assessee_name'] ?? $registration->candidate?->name ?? '-',
            'tanggal'          => $header['assessment_date'] ?? now()->format('d-m-Y'),
            
            'asesi_signature'  => $asesiSig['signature'] ?? null,
            'asesor_reg'       => $asesorReg,
            'asesor_signature' => $asesorSignature,
            'asesor_qr_code'   => $asesorQrCode,

            'product_details' => $productDetails,
            'product_reviews' => $formattedReviews,
            'assessment_notes' => $mainData['assessment_notes'] ?? '',
            'recommendation' => [
                'is_competent' => filter_var($recommendation['is_competent'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true,
                'is_not_competent' => filter_var($recommendation['is_competent'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false,
                'observation_details' => $recommendation['observation_details'] ?? [],
            ],

            'compilers' => $docControl['compilers'] ?? [],
            'validators' => $docControl['validators'] ?? [],
        ];

        $pdf = Pdf::loadView('forms.IA.ia11', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('defaultFont', 'Arial')
            ->setWarnings(false);

        $filename = 'FR_IA_11_' . str_replace([' ', '/', '\\'], '_', $data['nama_asesi']) . '_' . date('YmdHis') . '.pdf';

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
