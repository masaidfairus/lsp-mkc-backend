<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapaTemplate extends Model
{
    protected $fillable = [
        'scheme_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
