<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkData extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'company_address',
        'company_contact',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
