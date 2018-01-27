<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  public function students()
  {
    return User::where([
                    'course_code' => $this->course_code,
                    'admin' => 0
                  ])->pluck('key');
  }
}
