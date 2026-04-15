<?php

namespace App\Models;

use App\Enums\AppealStatus;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    protected $fillable = [
        'registration_id',
        'ak04_data',
        'status',
    ];

    protected $casts = [
        'status' => AppealStatus::class,
        'ak04_data' => 'array'
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
