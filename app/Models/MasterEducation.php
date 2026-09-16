<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterEducation extends Model
{
  protected $table = 'master_education';

  protected $fillable = [
    'title',
  ];

  /**
   * Mendapatkan relasi profiles.
   *
   * @return mixed
   */
  public function profiles()
  {
    return $this->hasMany(Profile::class, 'education_id');
  }

  /**
   * Mendapatkan relasi apl01Data.
   *
   * @return mixed
   */
  public function apl01Data()
  {
    return $this->hasMany(Apl01Data::class, 'education_id');
  }
}
