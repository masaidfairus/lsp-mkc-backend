<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentList extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function basicRequirementProofs()
    {
        return $this->hasMany(BasicRequirementProof::class, 'list_id');
    }

    public function administrativeProofs()
    {
        return $this->hasMany(AdministrativeProof::class, 'list_id');
    }

    public function portfolioScheme()
    {
        return $this->hasMany(PortfolioScheme::class, 'list_id');
    }

    public function fileProfile()
    {
        return $this->hasMany(FileProfile::class, 'list_id');
    }
}
