<?php

namespace App\Http\Controllers;

use App\Models\SignatureRequest;
use Illuminate\Http\Request;

class SignatureVerificationController extends Controller
{
    /**
     * Menampilkan detail dari resource yang ditentukan.
     *
     * @param  mixed  $id
     * @return mixed
     */
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

    /**
     * Stream.
     *
     * @param  mixed  $id
     * @param  mixed  $type
     * @return mixed
     */
    public function stream($id, $type)
    {
        $path = null;
        $isBase64 = false;

        if ($type === 'apl01') {
            $reg = \App\Models\AssessmentRegistration::with(['aplData'])->find($id);
            $aplSig = $reg?->aplData?->asesi_signature;

            // Cek apakah path file ada di storage lokal
            if ($aplSig && \Illuminate\Support\Facades\Storage::disk('local')->exists($aplSig)) {
                $path = $aplSig;
            } else {
                // Fallback ke UserSignature dari profil asesi
                $userSig = \App\Models\UserSignature::where('user_id', $reg?->user_id)->latest()->first();
                $sigData = $userSig?->signature_image;

                if ($sigData) {
                    // Deteksi apakah data base64
                    if (str_starts_with($sigData, 'data:') || base64_decode($sigData, true) !== false && !file_exists($sigData)) {
                        // Kembalikan sebagai response base64
                        if (str_starts_with($sigData, 'data:')) {
                            // Format: data:image/png;base64,xxxxx
                            $parts = explode(',', $sigData, 2);
                            $mime = str_replace(['data:', ';base64'], '', $parts[0]);
                            $imgData = base64_decode($parts[1]);
                        } else {
                            $mime = 'image/png';
                            $imgData = base64_decode($sigData);
                        }
                        return response($imgData, 200)->header('Content-Type', $mime);
                    } elseif (\Illuminate\Support\Facades\Storage::disk('local')->exists($sigData)) {
                        $path = $sigData;
                    }
                }
            }
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
