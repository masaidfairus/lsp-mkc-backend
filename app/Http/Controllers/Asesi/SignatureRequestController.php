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
        if ($signable && (isset($signable->main_data) || (method_exists($signable, 'getAttribute') && $signable->getAttribute('main_data')))) {
            $details = $signable->main_data;
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

        return $this->successResponse([
            'id' => $signatureRequest->id,
            'document_label' => $signatureRequest->document_label,
            'status' => $signatureRequest->status,
            'signable_type' => $signatureRequest->signable_type,
            'signable_data' => $signatureRequest->signable,
            'main_data' => $mainData
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

        if ($signatureRequest->status !== 'pending') {
            return $this->errorResponse('Tanda tangan sudah diselesaikan sebelumnya', 400);
        }

        $validated = $request->validate([
            'signature' => ['required', 'string'],
        ]);

        $base64Image = $validated['signature'];
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        }
        $image = base64_decode($base64Image);
        
        $sigName = "sig_user_{$user->id}_req_{$signatureRequest->id}_" . uniqid() . ".png";
        $sigPath = "signatures/" . $sigName;
        
        Storage::disk('local')->put($sigPath, $image);
        
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
