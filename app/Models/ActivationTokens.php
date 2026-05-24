<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationTokens extends Model
{
    /**
     * Mendapatkan relasi user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
