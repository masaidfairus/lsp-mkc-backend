<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\AssessmentStatus;
use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak01Controller extends Controller
{
    public function show(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if (!$registration->status->isApl01Complete()) {
            return $this->errorResponse('E2009', 403);
        }

        if (!$registration->status->isApl02Complete()) {
            return $this->errorResponse('E2018', 403);
        }

        if (!$registration->assessor_id) {
            return $this->errorResponse('E2019', 403);
        }

        return $this->successResponse($registration->plannings->first()->ak01_data);
    }
    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if (!$registration->status->isApl01Complete()) {
            return $this->errorResponse('E2009', 403);
        }

        if (!$registration->status->isApl02Complete()) {
            return $this->errorResponse('E2018', 403);
        }

        if (!$registration->assessor_id) {
            return $this->errorResponse('E2019', 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'signature' => ['required', 'string'],
        ]);

        $record = $registration->plannings->first();
        $data = $record->ak01_data ?? [];

        // --- Signature Processing ---
        $base64Image = $validated['signature'];
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        }
        $image = base64_decode($base64Image);
        $user = auth()->user();
        $sigName = "sig_user_{$user->id}_ak01_" . uniqid() . ".png";
        $sigPath = "signatures/" . $sigName;
        
        \Illuminate\Support\Facades\Storage::disk('local')->put($sigPath, $image);
        
        // Generate QR Code
        $qrText = route('signature.verify', ['id' => $registration->id, 'type' => 'ak01']);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($qrText));

        $data['signatures']['asesi'] = [
            'name' => $validated['name'],
            'date' => $validated['date'],
            'signature' => $sigPath,
            'qr_code' => $qrBase64,
        ];

        $record->ak01_data = $data;
        $record->ak01_asesi_signature = $sigPath;
        $record->ak01_asesi_qr_code = $qrBase64;
        $record->ak01_asesi_signature_date = $validated['date'] ?? now();
        $record->save();
        
        // Update registration status to APL02_APPROVED or similar if needed?
        // Usually AK.01 is part of the planning.

        return $this->successResponse(
            $record->ak01_data,
            'Data AK.01 berhasil disimpan'
        );
    }
}
