<?php

namespace App\Models;

use App\Enums\AppealStatus;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    protected $fillable = [
        'registration_id',
        'appeal_reason',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'status' => AppealStatus::class
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }
}
