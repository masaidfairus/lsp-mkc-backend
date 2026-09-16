<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesiPassword extends Model
{
    protected $fillable = [
        'user_id',
        'asesi_password'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
