<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SignatureRequest extends Model
{
    protected $fillable = [
        'signable_type',
        'signable_id',
        'registration_id',
        'document_label',
        'user_id',
        'role',
        'status',
        'raw_signature',
        'qr_code',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Polymorphic: bisa ke AssessmentInstrument, AssessmentPlanning, dll.
     */
    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSigned(): bool
    {
        return $this->status === 'signed';
    }
}
