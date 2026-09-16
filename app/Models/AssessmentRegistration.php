<?php

namespace App\Models;

use App\Enums\AssessmentStatus;
use App\Enums\CertificationPurpose;
use App\Enums\Result;
use Illuminate\Database\Eloquent\Model;

class AssessmentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'scheme_id',
        'assessor_id',
        'tuk',
        'status',
        'status_apl01',
        'status_apl02',
        'certification_purpose',
        'asesi_type',
        'institution_name',
        'final_result',
        'assessed_at',
        'admin_notes',
        'apl02_signature',
        'apl02_qr_code',
        'apl02_signature_date',
        'certificate',
        'is_apl02_continuable',
        'apl02_approach',
    ];

    protected $casts = [
        'status' => AssessmentStatus::class,
        'status_apl01' => \App\Enums\Apl01Status::class,
        'status_apl02' => \App\Enums\Apl02Status::class,
        'certification_purpose' => CertificationPurpose::class,
        'assessed_at' => 'datetime',
        'apl02_signature_date' => 'datetime',
        'is_apl02_continuable' => 'boolean',
    ];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'registration_id');
    }

    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class, 'registration_id');
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'registration_id');
    }

    public function payments()
    {
        return $this->hasOne(Payment::class, 'registration_id');
    }

    public function aplData()
    {
        return $this->hasOne(Apl01Data::class, 'registration_id');
    }

    public function aplPortfolio()
    {
        return $this->hasMany(Apl02Portfolio::class, 'registration_id');
    }

    public function filteredUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->where(function ($query) {
                $query->whereHas('documentList.basicRequirementProof', function ($q) {
                    $q->where('scheme_id', $this->scheme_id);
                })
                    ->orWhereHas('documentList.administrativeProof'); // Dan semua yang tipe administratif
            });
    }

    public function filteredAplUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList', function ($query) {
                $query->has('basicRequirementProof')
                    ->orHas('administrativeProof');
            });
    }

    public function portfolioFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList.portfolioScheme', function ($query) {
                $query->where('scheme_id', $this->scheme_id);
            });
    }

    public function reports()
    {
        return $this->hasMany(AssessmentReport::class, 'registration_id');
    }

    public function instruments()
    {
        return $this->hasMany(AssessmentInstrument::class, 'registration_id');
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'registration_id');
    }

    public function validations()
    {
        return $this->hasMany(AssessmentValidation::class, 'registration_id');
    }

    public function signatureRequests()
    {
        return $this->hasMany(SignatureRequest::class, 'registration_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            // Sinkronisasi status global berdasarkan status sub-form (APL.01 & APL.02)
            // Prioritas status APL.02 karena ini adalah tahapan selanjutnya
            if ($model->status_apl02 === \App\Enums\Apl02Status::APPROVED) {
                $model->status = \App\Enums\AssessmentStatus::APL02_APPROVED;
            } elseif ($model->status_apl02 === \App\Enums\Apl02Status::REJECTED) {
                $model->status = \App\Enums\AssessmentStatus::APL02_REJECTED;
            } elseif ($model->status_apl02 === \App\Enums\Apl02Status::SUBMITTED) {
                $model->status = \App\Enums\AssessmentStatus::APL02_SUBMITTED;
            } 
            // Jika APL.02 belum ada tindakan, gunakan status APL.01
            else {
                if ($model->status_apl01 === \App\Enums\Apl01Status::APPROVED) {
                    $model->status = \App\Enums\AssessmentStatus::APL01_APPROVED;
                } elseif ($model->status_apl01 === \App\Enums\Apl01Status::REJECTED) {
                    $model->status = \App\Enums\AssessmentStatus::APL01_REJECTED;
                } elseif ($model->status_apl01 === \App\Enums\Apl01Status::PAID) {
                    $model->status = \App\Enums\AssessmentStatus::APL01_PAID;
                } elseif ($model->status_apl01 === \App\Enums\Apl01Status::PENDING) {
                    $model->status = \App\Enums\AssessmentStatus::APL01_PENDING;
                } else {
                    // Jika tidak ada yang cocok, biarkan status yang ada atau set ke draft jika baru
                    if (!$model->exists && !$model->status) {
                        $model->status = \App\Enums\AssessmentStatus::APL01_DRAFT;
                    }
                }
            }
        });

        static::saved(function ($model) {
            // 1. Bersihkan permintaan TTD Asesor yang lama jika asesor_id diganti atau dihapus
            \App\Models\SignatureRequest::where('registration_id', $model->id)
                ->where('role', 'Asesor')
                ->when($model->assessor_id, fn($q) => $q->where('user_id', '!=', $model->assessor_id))
                ->delete();

            // Jika asesor tidak ada, stop
            if (!$model->assessor_id) {
                return;
            }

            // 2. Sinkronkan permintaan TTD secara bertahap
            // APL.01: Butuh TTD jika status PENDING/PAID/APPROVED
            // APL.02: Butuh TTD jika status SUBMITTED/APPROVED
            $docs = [
                'APL.01' => in_array($model->status_apl01, [\App\Enums\Apl01Status::PENDING, \App\Enums\Apl01Status::PAID, \App\Enums\Apl01Status::APPROVED]),
                'APL.02' => in_array($model->status_apl02, [\App\Enums\Apl02Status::SUBMITTED, \App\Enums\Apl02Status::APPROVED]),
            ];

            foreach ($docs as $label => $shouldHaveRequest) {
                if ($shouldHaveRequest) {
                    $existing = \App\Models\SignatureRequest::where([
                        'registration_id' => $model->id,
                        'user_id' => $model->assessor_id,
                        'document_label' => $label,
                    ])->first();

                    if (!$existing) {
                        \App\Models\SignatureRequest::create([
                            'registration_id' => $model->id,
                            'user_id' => $model->assessor_id,
                            'document_label' => $label,
                            'status' => 'pending',
                            'signable_type' => get_class($model),
                            'signable_id' => $model->id,
                            'role' => 'Asesor',
                        ]);
                    }
                } else {
                    // Jika status tidak memenuhi syarat, hapus request yang masih pending
                    \App\Models\SignatureRequest::where([
                        'registration_id' => $model->id,
                        'user_id' => $model->assessor_id,
                        'document_label' => $label,
                        'status' => 'pending',
                    ])->delete();
                }
            }
        });
    }
}
