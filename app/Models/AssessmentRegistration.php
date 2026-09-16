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

    /**
     * Mendapatkan relasi candidate.
     *
     * @return mixed
     */
    public function candidate()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Mendapatkan relasi assessor.
     *
     * @return mixed
     */
    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id', 'id');
    }

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    /**
     * Mendapatkan relasi selfAssessments.
     *
     * @return mixed
     */
    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi plannings.
     *
     * @return mixed
     */
    public function plannings()
    {
        return $this->hasMany(AssessmentPlanning::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi appeals.
     *
     * @return mixed
     */
    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi payments.
     *
     * @return mixed
     */
    public function payments()
    {
        return $this->hasOne(Payment::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi aplData.
     *
     * @return mixed
     */
    public function aplData()
    {
        return $this->hasOne(Apl01Data::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi aplPortfolio.
     *
     * @return mixed
     */
    public function aplPortfolio()
    {
        return $this->hasMany(Apl02Portfolio::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi filteredUserFiles.
     *
     * @return mixed
     */
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

    /**
     * Mendapatkan relasi filteredAplUserFiles.
     *
     * @return mixed
     */
    public function filteredAplUserFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList', function ($query) {
                $query->has('basicRequirementProof')
                    ->orHas('administrativeProof');
            });
    }

    /**
     * Mendapatkan relasi portfolioFiles.
     *
     * @return mixed
     */
    public function portfolioFiles()
    {
        return $this->hasMany(FileProfile::class, 'user_id', 'user_id')
            ->whereHas('documentList.portfolioScheme', function ($query) {
                $query->where('scheme_id', $this->scheme_id);
            });
    }

    /**
     * Mendapatkan relasi reports.
     *
     * @return mixed
     */
    public function reports()
    {
        return $this->hasMany(AssessmentReport::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi instruments.
     *
     * @return mixed
     */
    public function instruments()
    {
        return $this->hasMany(AssessmentInstrument::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi results.
     *
     * @return mixed
     */
    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi validations.
     *
     * @return mixed
     */
    public function validations()
    {
        return $this->hasMany(AssessmentValidation::class, 'registration_id');
    }

    /**
     * Mendapatkan relasi signatureRequests.
     *
     * @return mixed
     */
    public function signatureRequests()
    {
        return $this->hasMany(SignatureRequest::class, 'registration_id');
    }

    /**
     * Booted.
     *
     * @return mixed
     */
    protected static function booted()
    {
        static::deleting(function ($model) {
            // Hapus file fisik dan record FileProfile terkait agar tidak meninggalkan jejak / file broken
            $files = \App\Models\FileProfile::where('user_id', $model->user_id)
                ->where(function ($query) use ($model) {
                    $query->where('upload_document', 'like', "bukti-admin/{$model->id}/%")
                          ->orWhere('upload_document', 'like', "bukti-dasar/{$model->id}/%");
                })
                ->get();
                
            foreach ($files as $file) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($file->upload_document);
                $file->delete();
            }
            
            // Hapus direktori fisik untuk registrasi ini
            \Illuminate\Support\Facades\Storage::disk('local')->deleteDirectory("bukti-admin/{$model->id}");
            \Illuminate\Support\Facades\Storage::disk('local')->deleteDirectory("bukti-dasar/{$model->id}");
            
            // Hapus file tanda tangan asesi dari APL01 Data
            $aplData = \App\Models\Apl01Data::where('registration_id', $model->id)->first();
            if ($aplData && $aplData->asesi_signature) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($aplData->asesi_signature);
            }

            // Cascade delete AssessmentInstruments related to this registration
            \App\Models\AssessmentInstrument::where('registration_id', $model->id)->get()->each(function ($instrument) {
                $instrument->delete(); // Use each()->delete() to trigger AssessmentInstrument's deleting event
            });

            // Cascade delete AssessmentPlanning related to this registration
            \App\Models\AssessmentPlanning::where('registration_id', $model->id)->get()->each(function ($planning) {
                $planning->delete(); // Use each()->delete() to trigger AssessmentPlanning's deleting event
            });

            // Cascade delete AssessmentResult related to this registration
            \App\Models\AssessmentResult::where('registration_id', $model->id)->get()->each(function ($result) {
                $result->delete();
            });

            // Cascade delete AssessmentReport related to this registration
            \App\Models\AssessmentReport::where('registration_id', $model->id)->get()->each(function ($report) {
                $report->delete();
            });

            // Delete signature requests attached directly to the registration
            $model->signatureRequests()->delete();
        });

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

            // 2. Sinkronkan permintaan TTD secara bertahap

            // APL.01: Butuh TTD dari Admin jika status PENDING/PAID/APPROVED
            $shouldHaveApl01 = in_array($model->status_apl01, [\App\Enums\Apl01Status::PENDING, \App\Enums\Apl01Status::PAID, \App\Enums\Apl01Status::APPROVED]);
            if ($shouldHaveApl01) {
                // Cari admin yang meng-acc APL.01 (dari user login, fallback ke admin pertama)
                $adminUser = auth()->user();
                if (!$adminUser || !$adminUser->hasRole(['admin', 'superadmin'])) {
                    $adminUser = \App\Models\User::role(['admin', 'superadmin'])->first();
                }
                $adminId = $adminUser?->id;

                if ($adminId) {
                    $existing = \App\Models\SignatureRequest::where([
                        'registration_id' => $model->id,
                        'document_label' => 'APL.01',
                        'role' => 'Admin',
                    ])->first();

                    if (!$existing) {
                        \App\Models\SignatureRequest::create([
                            'registration_id' => $model->id,
                            'user_id' => $adminId,
                            'document_label' => 'APL.01',
                            'status' => 'pending',
                            'signable_type' => get_class($model),
                            'signable_id' => $model->id,
                            'role' => 'Admin',
                        ]);
                    } else {
                        // Jika request masih pending dan yang menyimpan adalah admin, update user_id ke admin tersebut
                        if ($existing->status === 'pending' && auth()->check() && auth()->user()->hasRole(['admin', 'superadmin'])) {
                            if ($existing->user_id !== auth()->id()) {
                                $existing->update(['user_id' => auth()->id()]);
                            }
                        }
                    }
                }
            } else {
                // Jika status tidak memenuhi syarat, hapus request APL.01 yang masih pending
                \App\Models\SignatureRequest::where([
                    'registration_id' => $model->id,
                    'document_label' => 'APL.01',
                    'role' => 'Admin',
                    'status' => 'pending',
                ])->delete();
            }

            // APL.02: Butuh TTD dari Asesor jika status SUBMITTED/APPROVED dan asesor_id ada
            $shouldHaveApl02 = in_array($model->status_apl02, [\App\Enums\Apl02Status::SUBMITTED, \App\Enums\Apl02Status::APPROVED]) && $model->assessor_id;
            if ($shouldHaveApl02) {
                $existing = \App\Models\SignatureRequest::where([
                    'registration_id' => $model->id,
                    'user_id' => $model->assessor_id,
                    'document_label' => 'APL.02',
                    'role' => 'Asesor',
                ])->first();

                if (!$existing) {
                    \App\Models\SignatureRequest::create([
                        'registration_id' => $model->id,
                        'user_id' => $model->assessor_id,
                        'document_label' => 'APL.02',
                        'status' => 'pending',
                        'signable_type' => get_class($model),
                        'signable_id' => $model->id,
                        'role' => 'Asesor',
                    ]);
                }
            } else {
                // Jika status tidak memenuhi syarat atau asesor tidak diset, hapus request APL.02 yang masih pending
                \App\Models\SignatureRequest::where([
                    'registration_id' => $model->id,
                    'document_label' => 'APL.02',
                    'role' => 'Asesor',
                    'status' => 'pending',
                ])->delete();
            }
        });
    }
}
