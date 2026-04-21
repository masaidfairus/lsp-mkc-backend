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

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function documentList()
    {
        return $this->belongsTo(DocumentList::class);
    }
}
