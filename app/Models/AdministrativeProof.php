<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeProof extends Model
{
    protected $fillable = [
        'list_id'
    ];

    public function documentList()
    {
        return $this->belongsTo(DocumentList::class);
    }
}
