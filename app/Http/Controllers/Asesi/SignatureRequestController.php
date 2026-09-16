<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignatureRequestController extends Controller
{
    /**
     * Menampilkan daftar resource.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $requests = SignatureRequest::where('user_id', $user->id)
            ->where('role', 'asesi')
            ->with(['signable'])
            ->latest()
            ->get();

        $pending = $requests->where('status', 'pending')->values();
        $signed = $requests->where('status', 'signed')->values();

        return $this->successResponse([
            'pending' => $pending,
            'signed' => $signed,
            'all' => $requests,
        ]);
    }

    /**
     * Menampilkan detail dari resource yang ditentukan.
     *
     * @param  Request  $request
     * @param  mixed  $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $signatureRequest = SignatureRequest::where('id', $id)
            ->where('user_id', $user->id)
            ->where('role', 'asesi')
            ->with(['signable'])
            ->first();

        if (!$signatureRequest) {
            return $this->errorResponse('Data permintaan tanda tangan tidak ditemukan atau bukan milik Anda', 404);
        }

        $reg = $signatureRequest->registration;
        $scheme = $reg?->scheme;
        $asesi = $reg?->candidate;
        $asesor = $reg?->assessor;

        $signable = $signatureRequest->signable;
        $details = [];
        
        if ($signable) {
            if (isset($signable->main_data) || (method_exists($signable, 'getAttribute') && $signable->getAttribute('main_data'))) {
                $details = $signable->main_data;
            } else {
                // For AssessmentPlanning, extract data from fields like ak01_data, ak07_data
                $labelStr = strtolower(str_replace(['.', '-'], '', $signatureRequest->document_label));
                // Handle FR. prefix (e.g. FR.AK.01 -> ak01_data)
                if (str_starts_with($labelStr, 'fr')) {
                    $labelStr = substr($labelStr, 2);
                }
                $dataField = $labelStr . '_data';
                
                if (method_exists($signable, 'getAttribute') && $signable->getAttribute($dataField)) {
                    $details = $signable->getAttribute($dataField);
                }
            }
        }

        $mainData = array_merge($details, [
            'header' => array_merge($details['header'] ?? [], [
                'certification_scheme' => $scheme?->name ?? $details['header']['certification_scheme'] ?? $details['header']['scheme_name'] ?? '-',
                'scheme_code' => $scheme?->code ?? $details['header']['scheme_code'] ?? $details['header']['scheme_number'] ?? '-',
                'tuk' => $reg?->tuk ?? $details['header']['tuk'] ?? '-',
                'assessor_name' => $asesor?->name ?? $details['header']['assessor_name'] ?? $details['header']['asesor_name'] ?? '-',
                'assessee_name' => $asesi?->name ?? $details['header']['assessee_name'] ?? $details['header']['asesi_name'] ?? '-',
                'assessment_date' => $reg?->created_at?->format('d F Y') ?? $details['header']['assessment_date'] ?? $details['header']['date'] ?? $details['header']['tanggal'] ?? '-',
            ])
        ]);

        // Build signature URL if already signed
        $signatureUrl = null;
        if ($signatureRequest->status === 'signed' && $signatureRequest->raw_signature) {
            // If it's a file path (not base64), generate a URL
            if (!str_starts_with($signatureRequest->raw_signature, 'data:')) {
                $signatureUrl = url('/storage/' . $signatureRequest->raw_signature);
            } else {
                $signatureUrl = $signatureRequest->raw_signature;
            }
        }

        return $this->successResponse([
            'id' => $signatureRequest->id,
            'document_label' => $signatureRequest->document_label,
            'status' => $signatureRequest->status,
            'signable_type' => $signatureRequest->signable_type,
            'signable_data' => $signatureRequest->signable,
            'main_data' => $mainData,
            'raw_signature' => $signatureUrl,
            'signed_at' => $signatureRequest->signed_at?->toIso8601String(),
            'qr_code' => $signatureRequest->qr_code,
        ]);
    }

    /**
     * Sign.
     *
     * @param  Request  $request
     * @param  mixed  $id
     * @return mixed
     */
    public function sign(Request $request, $id)
    {
        $user = $request->user();

        $signatureRequest = SignatureRequest::where('id', $id)
            ->where('user_id', $user->id)
            ->where('role', 'asesi')
            ->first();

        if (!$signatureRequest) {
            return $this->errorResponse('Data permintaan tanda tangan tidak ditemukan atau bukan milik Anda', 404);
        }

        $reg = $signatureRequest->registration;
        if ($reg) {
            $documentLabel = strtoupper($signatureRequest->document_label);
            if (!in_array($documentLabel, ['APL.01', 'FR.APL.01', 'APL.02', 'FR.APL.02'])) {
                if (!$reg->status || !$reg->status->isApl02Complete()) {
                    return $this->errorResponse('Tanda tangan tidak dapat diselesaikan karena APL-02 belum disetujui.', 403);
                }
            }
        }

        if ($signatureRequest->status !== 'pending') {
            return $this->errorResponse('Tanda tangan sudah diselesaikan sebelumnya', 400);
        }

        $validated = $request->validate([
            'signature' => ['nullable', 'string'],
        ]);

        if (empty($validated['signature'])) {
            $userSignature = \App\Models\UserSignature::where('user_id', $user->id)->first();
            if (!$userSignature || empty($userSignature->signature_image)) {
                return $this->errorResponse('Tanda tangan master tidak ditemukan. Silakan lengkapi profil terlebih dahulu.', 400);
            }
            $sigPath = $userSignature->signature_image;
        } else {
            $base64Image = $validated['signature'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            }
            $image = base64_decode($base64Image);
            
            $sigName = "sig_user_{$user->id}_req_{$signatureRequest->id}_" . uniqid() . ".png";
            $sigPath = "signatures/" . $sigName;
            
            Storage::disk('local')->put($sigPath, $image);
        }
        
        // Generate QR Code (SVG is safer if imagick is missing)
        $qrText = route('signature.verify', ['id' => $signatureRequest->id]);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(200)->generate($qrText));

        $signatureRequest->update([
            'raw_signature' => $sigPath,
            'qr_code' => $qrBase64,
            'status' => 'signed',
            'signed_at' => now(),
        ]);

        // Sync signature back to signable main_data if it exists (for AK series)
        $signable = $signatureRequest->signable;
        if ($signable && $signatureRequest->role === 'asesi') {
            if (isset($signable->main_data) || (method_exists($signable, 'getAttribute') && $signable->getAttribute('main_data'))) {
                $mainData = $signable->main_data;
                $mainData['signatures']['asesi'] = [
                    'signature' => $sigPath,
                    'qr_code' => $qrBase64,
                    'date' => now()->format('Y-m-d'),
                ];
                $signable->main_data = $mainData;
                $signable->save();
            } elseif (get_class($signable) === \App\Models\AssessmentPlanning::class) {
                $cleanLabel = str_replace('FR.', '', $signatureRequest->document_label);
                if ($cleanLabel === 'AK.01') {
                    $data = $signable->ak01_data ?? [];
                    $data['signatures']['asesi'] = [
                        'name' => $user->name,
                        'date' => now()->toDateString(),
                        'signature' => $sigPath,
                        'qr_code' => $qrBase64,
                    ];
                    $signable->ak01_data = $data;
                    $signable->ak01_asesi_signature = $sigPath;
                    $signable->ak01_asesi_qr_code = $qrBase64;
                    $signable->ak01_asesi_signature_date = now();
                    $signable->save();
                } elseif ($cleanLabel === 'AK.07') {
                    $data = $signable->ak07_data ?? [];
                    $data['signatures']['asesi'] = [
                        'name' => $user->name,
                        'date' => now()->toDateString(),
                        'signature' => $sigPath,
                        'qr_code' => $qrBase64,
                    ];
                    $signable->ak07_data = $data;
                    $signable->save();
                } elseif ($cleanLabel === 'MAPA.01') {
                    $data = $signable->mapa01_data ?? [];
                    $data['signatures']['asesi'] = [
                        'name' => $user->name,
                        'date' => now()->toDateString(),
                        'signature' => $sigPath,
                        'qr_code' => $qrBase64,
                    ];
                    $signable->mapa01_data = $data;
                    $signable->save();
                }
            } elseif (get_class($signable) === \App\Models\AssessmentRegistration::class) {

                // Special case for APL01/APL02 if signable is registration
                if ($signatureRequest->document_label === 'APL.01') {
                    $aplData = $signable->aplData;
                    if ($aplData) {
                        $aplData->update([
                            'asesi_signature' => $sigPath,
                            'asesi_qr_code' => $qrBase64,
                            'asesi_signature_date' => now(),
                        ]);
                    }
                } elseif ($signatureRequest->document_label === 'APL.02') {
                    $signable->update([
                        'apl02_signature' => $sigPath,
                        'apl02_qr_code' => $qrBase64,
                        'apl02_signature_date' => now(),
                    ]);
                }
            }
        }

        return $this->successResponse(
            null,
            'Tanda tangan berhasil disimpan'
        );
    }
}
