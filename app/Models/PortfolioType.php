<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioType extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'created_by');
    }
}
