<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Class extends Model
{
    public function students()
    {
      return \DB::table('students_in_classes')
                  ->where('course_code', $this->course_code)
                  ->pluck('student_key')
                  ->toArray();
    }
}
