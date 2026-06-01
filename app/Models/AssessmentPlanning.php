<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AssessmentPlanning extends Model
{
    protected $fillable = [
        'registration_id',
        'mapa01_data',
        'mapa02_data',
        'ak01_data',
        'ak07_data',
        'version',
        'confirmed_at',
        'ak01_asesi_signature',
        'ak01_asesi_qr_code',
        'ak01_asesi_signature_date',
    ];

    protected $casts = [
        'mapa01_data' => 'array',
        'mapa02_data' => 'array',
        'ak01_data' => 'array',
        'ak07_data' => 'array',
        'confirmed_at' => 'datetime',
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

            // List of documents stored in AssessmentPlanning
            $docs = [
                'ak01_data'   => 'AK.01',
                'ak07_data'   => 'AK.07',
                'mapa01_data' => 'MAPA.01',
                'mapa02_data' => 'MAPA.02',
            ];

            foreach ($docs as $field => $label) {
                if ($model->$field !== null) {
                    $existing = SignatureRequest::where([
                        'registration_id' => $model->registration_id,
                        'user_id'         => $reg->assessor_id,
                        'document_label'  => $label,
                        'role'            => 'Asesor',
                    ])->first();

                    if (!$existing) {
                        SignatureRequest::create([
                            'registration_id' => $model->registration_id,
                            'user_id'         => $reg->assessor_id,
                            'document_label'  => $label,
                            'status'          => 'pending',
                            'signable_type'   => get_class($model),
                            'signable_id'     => $model->id,
                            'role'            => 'Asesor',
                        ]);
                    }
                }
            }
        });

        static::deleting(function ($model) {
            SignatureRequest::where('registration_id', $model->registration_id)
                ->whereIn('document_label', ['AK.01', 'FR.AK.01', 'AK.07', 'FR.AK.07', 'MAPA.01', 'FR.MAPA.01', 'MAPA.02', 'FR.MAPA.02'])
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
        return $this->belongsTo(AssessmentRegistration::class);
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
