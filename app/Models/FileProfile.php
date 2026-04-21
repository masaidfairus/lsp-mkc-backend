<?php

namespace App\Models;

use App\Enums\Apl01Status;
use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
use Illuminate\Database\Eloquent\Model;

class FileProfile extends Model
{
    protected $fillable = [
        'user_id',
        'list_id',
        'upload_document',
        'status',
        'validation_result',
        'admin_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'status' => EvidenceStatus::class,
        'validation_result' => ValidationResult::class,
        'verified_at' => 'datetime',
    ];

    public function documentList()
    {
        return $this->belongsTo(DocumentList::class, 'list_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function apl02Portfolio()
    {
        return $this->hasMany(Apl02Portfolio::class);
    }
}
