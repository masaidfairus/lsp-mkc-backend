<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'document_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

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
