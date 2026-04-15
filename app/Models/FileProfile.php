<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileProfile extends Model
{
    protected $fillable = [
        'user_id',
        'list_id',
        'upload_document',
    ];

    protected $casts = [
        'is_valid_admin' => 'boolean'
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
