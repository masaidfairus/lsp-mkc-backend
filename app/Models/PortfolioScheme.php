<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioScheme extends Model
{
    protected $fillable = [
        'scheme_id',
        'list_id',
        'is_mandatory'
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function documentList()
    {
        return $this->belongsTo(DocumentList::class, 'list_id');
    }
}
