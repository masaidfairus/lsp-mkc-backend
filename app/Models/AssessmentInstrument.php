<?php

namespace App\Models;

use App\Models\AssessmentRegistration;
use App\Models\SignatureRequest;
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
     * Booted.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saved(function ($model) {
            $reg = AssessmentRegistration::find($model->registration_id);
            if (!$reg || !$reg->assessor_id) return;

            // Determine role and label based on document_type
            $documentLabel = str_replace('FR.', '', $model->document_type);
            
            $existing = SignatureRequest::where([
                'registration_id' => $model->registration_id,
                'user_id'         => $reg->assessor_id,
                'document_label'  => $documentLabel,
            ])->first();

            if (!$existing) {
                SignatureRequest::create([
                    'registration_id' => $model->registration_id,
                    'user_id'         => $reg->assessor_id,
                    'document_label'  => $documentLabel,
                    'status'          => 'pending',
                    'signable_type'   => get_class($model),
                    'signable_id'     => $model->id,
                    'role'            => 'Asesor',
                ]);
            }
        });
    }

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
}
