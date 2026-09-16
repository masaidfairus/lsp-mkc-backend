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
        'original_name',
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

    /**
     * Mendapatkan relasi documentList.
     *
     * @return mixed
     */
    public function documentList()
    {
        return $this->belongsTo(DocumentList::class, 'list_id');
    }

    /**
     * Mendapatkan relasi user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Mendapatkan relasi apl02Portfolio.
     *
     * @return mixed
     */
    public function apl02Portfolio()
    {
        return $this->hasMany(Apl02Portfolio::class, 'list_id');
    }
}
