<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'scheme_id',
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

    public function apl02Portolio()
    {
        return $this->hasMany(Apl02Portfolio::class);
    }
}
