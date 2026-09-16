<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Carbon\Carbon;

abstract class Controller
{
    //
    use ApiResponse;

    public function convertDateToLocalTime($rawDate)
    {
        return Carbon::parse($rawDate)
            ->locale('id')
            ->settings(['formatFunction' => 'translatedFormat'])
            ->format('l, d F Y');
    }

    public function getAssessorSignatureData($assessor, $registrationId = null, $docLabel = null, $requireSigned = false)
    {
        if (!$assessor) {
            return [
                'name' => '-',
                'reg_number' => '-',
                'qr_code' => null,
                'signature' => null,
                'is_signed' => false,
            ];
        }

        return $this->resolveSignature($assessor->id, $registrationId, $docLabel, $requireSigned, $assessor->name, $assessor->registration_number);
    }

    public function getUserSignature($userId, $registrationId = null, $docLabel = null, $requireSigned = false)
    {
        if (!$userId) {
            return ['signature' => null, 'qr_code' => null, 'is_signed' => false];
        }

        $user = \App\Models\User::find($userId);
        return $this->resolveSignature($userId, $registrationId, $docLabel, $requireSigned, $user?->name, $user?->registration_number);
    }

    protected function resolveSignature($userId, $registrationId, $docLabel, $requireSigned, $name = null, $regNumber = null)
    {
        $sig = null;

        // Coba cari signature request yang sudah di-sign
        if ($registrationId && $docLabel) {
            $req = \App\Models\SignatureRequest::where('registration_id', $registrationId)
                ->where('user_id', $userId)
                ->where('document_label', $docLabel)
                ->where('status', 'signed')
                ->latest('signed_at')
                ->first();
            
            if ($req) {
                $sig = (object) [
                    'qr_code' => $req->qr_code,
                    'signature' => $req->raw_signature
                ];
            }
        }

        // Fallback ke library tanda tangan lama (Hanya jika tidak dipaksa harus signed per dokumen)
        if (!$sig && !$requireSigned) {
            $sig = \Illuminate\Support\Facades\DB::table('user_signatures')
                ->where('user_id', $userId)
                ->orWhere('name', $name)
                ->latest('created_at')
                ->first();
        }

        return [
            'name' => $name ?? '-',
            'reg_number' => $regNumber ?? '-',
            'qr_code' => $sig ? ($sig->qr_code ?? null) : null,
            'signature' => $sig ? ($sig->signature_image ?? ($sig->signature ?? ($sig->raw_signature ?? null))) : null,
            'is_signed' => $sig !== null,
            'signed_at' => ($req ?? null) ? $req->signed_at : null,
        ];
    }

    public function isAuthorizedReviewer($user, $registration)
    {
        // ✅ Berikan akses jika request memiliki signature yang valid (untuk mobile/link eksternal)
        if (request()->hasValidSignature()) {
            return true;
        }

        if (!$user) return false;

        // Admin selalu boleh
        if (in_array($user->role, ['admin', 'superadmin']) || (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('superadmin')))) {
            return true;
        }

        // Asesor yang ditugaskan boleh tinjau (meskipun kolom role database salah isinya)
        if ($registration && $user->id === $registration->assessor_id) {
            return true;
        }

        return false;
    }
}
