<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AssessmentInstrument extends Model
{
    protected $fillable = [
        'registration_id',
        'document_type',
        'main_data',
        'validation_data',
    ];

    protected $casts = [
        'main_data' => 'array',
        'validation_data' => 'array',
    ];

    /**
     * Mendapatkan relasi registration.
     *
     * @return mixed
     */
    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }

    /**
     * Signature requests.
     *
     * @return MorphMany
     */
    public function signatureRequests(): MorphMany
    {
        return $this->morphMany(SignatureRequest::class, 'signable');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            // Delete associated signature requests
            $model->signatureRequests()->delete();
        });
    }
}
