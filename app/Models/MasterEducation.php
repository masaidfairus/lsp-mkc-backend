<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterEducation extends Model
{
  protected $table = 'master_education';

  protected $fillable = [
    'title',
  ];

  public function profiles()
  {
    return $this->hasMany(Profile::class, 'education_id');
  }

  public function apl01Data()
  {
    return $this->hasMany(Apl01Data::class, 'education_id');
  }
}
