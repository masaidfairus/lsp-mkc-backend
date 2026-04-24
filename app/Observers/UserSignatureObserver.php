<?php

namespace App\Observers;

use App\Models\UserSignature;

class UserSignatureObserver
{
    public function saved(UserSignature $userSignature): void
    {
        if ($userSignature->wasChanged(['signature_image', 'signature_shape', 'name']) || $userSignature->wasRecentlyCreated) {
            
            // Generate QR content with ID
            $qrContent = route('signature.verify', ['id' => $userSignature->id]);

            $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->generate($qrContent);

            $userSignature->qr_code = 'data:image/svg+xml;base64,' . base64_encode($qrImage);
            $userSignature->saveQuietly();
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
