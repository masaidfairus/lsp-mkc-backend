<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'invoice_number',
        'amount',
        'status',
        'payment_proof_url',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }
}
