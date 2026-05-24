<?php

namespace App\Models;

use App\Models\AssessmentRegistration;
use App\Models\SignatureRequest;

class Ak02 extends AssessmentResult
{
    protected $table = 'assessment_results';

    /**
     * Booted.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('document_type', fn ($q) => $q->where('document_type', 'FR.AK.02'));
        static::creating(fn ($m) => $m->document_type = 'FR.AK.02');

        static::saved(function ($model) {
            $reg = AssessmentRegistration::find($model->registration_id);
            if (!$reg || !$reg->assessor_id) return;

            $existing = SignatureRequest::where([
                'registration_id' => $model->registration_id,
                'user_id'         => $reg->assessor_id,
                'document_label'  => 'AK.02',
            ])->first();

            if (!$existing) {
                SignatureRequest::create([
                    'registration_id' => $model->registration_id,
                    'user_id'         => $reg->assessor_id,
                    'document_label'  => 'AK.02',
                    'status'          => 'pending',
                    'signable_type'   => get_class($model),
                    'signable_id'     => $model->id,
                    'role'            => 'Asesor',
                ]);
            }
        });

        // ✅ CASCADE DELETE: Hapus semua SignatureRequest terkait saat AK.02 dihapus
        // (baik dari Filament maupun via API), agar status mobile selalu akurat.
        static::deleting(function ($model) {
            SignatureRequest::where('registration_id', $model->registration_id)
                ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
                ->delete();
        });
    }

    /**
     * Mendapatkan relasi candidate.
     *
     * @return mixed
     */
    public function candidate()
    {
        return $this->hasOneThrough(
            User::class,
            AssessmentRegistration::class,
            'id',
            'id',
            'registration_id',
            'user_id'
        );
    }

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->hasOneThrough(
            Scheme::class,
            AssessmentRegistration::class,
            'id',
            'id',
            'registration_id',
            'scheme_id'
        );
    }
}
