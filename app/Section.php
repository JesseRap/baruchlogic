<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  public function students()
  {
    return $this->hasMany('App\User::where("admin", 1)');
  }
}
