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
}
