<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
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

            $documentLabel = str_replace('FR.', '', $model->document_type);

            $existing = SignatureRequest::where([
                'registration_id' => $model->registration_id,
                'user_id'         => $reg->assessor_id,
                'document_label'  => $documentLabel,
                'role'            => 'Asesor',
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

        static::deleting(function ($model) {
            $documentLabel = str_replace('FR.', '', $model->document_type);
            SignatureRequest::where('registration_id', $model->registration_id)
                ->where(function ($q) use ($documentLabel, $model) {
                    $q->where('document_label', $documentLabel)
                      ->orWhere('document_label', $model->document_type);
                })
                ->delete();
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
}
