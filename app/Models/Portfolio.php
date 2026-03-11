<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'portfolio_type_id',
        'file_path',
        'file_url',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function portfolioType()
    {
        return $this->belongsTo(PortfolioType::class);
    }

    public function selfAssessment()
    {
        return $this->hasMany(SelfAssessment::class);
    }
}
