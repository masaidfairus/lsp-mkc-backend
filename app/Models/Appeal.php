<?php
 
namespace App\Models;
 
use App\Enums\AppealStatus;
use Illuminate\Database\Eloquent\Model;
 
class Appeal extends Model
{
    protected $fillable = [
        'registration_id',
        'ak04_data',
        'status',
        'asesi_signature',
        'asesi_qr_code',
        'asesi_signature_date',
    ];
 
    protected $casts = [
        'status' => AppealStatus::class,
        'ak04_data' => 'array'
    ];
 
    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AppealStatus::APPROVED;
    }
 
    protected static function booted()
    {
        static::updated(function ($appeal) {
            // Jika status berubah menjadi APPROVED (Diterima)
            if ($appeal->isDirty('status') && $appeal->status === AppealStatus::APPROVED) {
                $registration = $appeal->registration;
                if ($registration && $registration->assessor_id) {
                    // Cek apakah sudah ada request ttd yang pending/signed
                    $exists = \App\Models\SignatureRequest::where('registration_id', $registration->id)
                        ->where('user_id', $registration->assessor_id)
                        ->where('document_label', 'AK.04')
                        ->exists();
                    
                    if (!$exists) {
                        \App\Models\SignatureRequest::create([
                            'registration_id' => $registration->id,
                            'user_id' => $registration->assessor_id,
                            'signable_type' => Appeal::class,
                            'signable_id' => $appeal->id,
                            'document_label' => 'AK.04',
                            'role' => 'Asesor',
                            'status' => 'pending',
                        ]);
                    }
                }
            }
        });
    }
}
