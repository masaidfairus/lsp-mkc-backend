<?php

namespace App\Http\Controllers;

use App\Models\SignatureRequest;
use Illuminate\Http\Request;

class SignatureVerificationController extends Controller
{
    public function show($id)
    {
        $signatureRequest = SignatureRequest::with(['user', 'registration'])->find($id);

        // Pastikan data ada dan statusnya memang sudah ditandatangani
        if (!$signatureRequest || $signatureRequest->status !== 'signed') {
            abort(404, 'Data tanda tangan tidak ditemukan atau belum ditandatangani.');
        }

        return view('pages.signature-verification', [
            'signature' => $signatureRequest,
            'user' => $signatureRequest->user,
            'registration' => $signatureRequest->registration,
        ]);
    }

    public function stream($id, $type)
    {
        $path = null;
        if ($type === 'apl01') {
            $reg = \App\Models\AssessmentRegistration::with('aplData')->find($id);
            $path = $reg?->aplData?->asesi_signature;
        } elseif ($type === 'apl02') {
            $reg = \App\Models\AssessmentRegistration::find($id);
            $path = $reg?->apl02_signature;
        } elseif ($type === 'ak04') {
            $reg = \App\Models\AssessmentRegistration::with('appeals')->find($id);
            $path = $reg?->appeals->first()?->ak04_data['signature']['asesi_signature'] ?? null;
        } elseif ($type === 'ak01') {
            $reg = \App\Models\AssessmentRegistration::with('plannings')->find($id);
            $path = $reg?->plannings->first()?->ak01_data['signatures']['asesi']['signature'] ?? null;
        }

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->response($path);
    }
}
