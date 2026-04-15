<?php

namespace App\Observers;

use App\Models\UserSignature;

class UserSignatureObserver
{
    public function saving(UserSignature $userSignature): void
    {
        if ($userSignature->isDirty(['signature_image', 'signature_shape', 'name'])) {
            $data = [
                'n' => $userSignature->name,
                'd' => now()->toDateTimeString(),
                's' => base64_encode(gzcompress($userSignature->signature_shape ?? '')),
            ];

            $qrContent = json_encode($data);
            
            // Generate QR as base64 image
            $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->generate($qrContent);
            
            $userSignature->qr_code = 'data:image/svg+xml;base64,' . base64_encode($qrImage);
        }
    }

    /**
     * Handle the UserSignature "updated" event.
     */
    public function updated(UserSignature $userSignature): void
    {
        //
    }

    /**
     * Handle the UserSignature "deleted" event.
     */
    public function deleted(UserSignature $userSignature): void
    {
        //
    }

    /**
     * Handle the UserSignature "restored" event.
     */
    public function restored(UserSignature $userSignature): void
    {
        //
    }

    /**
     * Handle the UserSignature "force deleted" event.
     */
    public function forceDeleted(UserSignature $userSignature): void
    {
        //
    }
}
