<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeProof extends Model
{
    protected $fillable = [
        'list_id'
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
}
