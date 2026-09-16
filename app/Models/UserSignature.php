<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSignature extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'signature_image',
        'signature_shape',
        'qr_code',
    ];

    /**
     * Mendapatkan relasi user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
