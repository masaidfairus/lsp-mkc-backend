<?php

namespace App\Observers;

use App\Models\UserSignature;

class UserSignatureObserver
{
   public function saving(UserSignature $userSignature): void
{
    // Pastikan model punya ID atau UUID sebelum membuat QR
    // Lebih aman menggunakan event "created" atau "updated" daripada "saving" 
    // jika kamu butuh ID yang sudah ter-generate (jika pakai auto-increment)
    
    if ($userSignature->isDirty(['signature_image', 'signature_shape', 'name'])) {
        
        // Cukup masukkan ID unik model ini ke dalam URL
        $qrContent = route('signature.verify', ['id' => $userSignature->id]);

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
