<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apl02Portfolio extends Model
{
    protected $fillable = [
        'registration_id',
        'file_profile_id',
        'list_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
     * Mendapatkan relasi fileProfile.
     *
     * @return mixed
     */
    public function fileProfile()
    {
        return $this->belongsTo(FileProfile::class, 'file_profile_id');
    }

    /**
     * Mendapatkan relasi documentList.
     *
     * @return mixed
     */
    public function documentList()
    {
        return $this->belongsTo(DocumentList::class, 'list_id');
    }
}
