<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicRequirementProof extends Model
{
    protected $fillable = [
        'scheme_id',
        'list_id',
        'is_mandatory',
        'or_group',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    /**
     * Mendapatkan relasi scheme.
     *
     * @return mixed
     */
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
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
