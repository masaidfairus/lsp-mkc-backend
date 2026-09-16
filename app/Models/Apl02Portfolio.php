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

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }

    public function fileProfile()
    {
        return $this->belongsTo(FileProfile::class, 'file_profile_id');
    }

    public function documentList()
    {
        return $this->belongsTo(DocumentList::class, 'list_id');
    }
}
