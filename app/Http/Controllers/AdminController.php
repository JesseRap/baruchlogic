<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;

use App\Problemset;


class AdminController extends Controller
{
    public function index()
    {

      if (!Auth::check() || !Auth::user()->admin === 1)
      {
        return redirect('/admin/login');
      }

      $instructor = User::where('key', Auth::user()->key)->first();

      $classes = \DB::table('sections')
                      ->where('instructor_id', $instructor->id)->get();

      $studentKeysInClasses = array();

      foreach ($classes as $class) {
        $studentKeysInClasses[$class->course_code] =
                                  \DB::table('users')
                                      ->where([
                                        'course_code' => $class->course_code,
                                        'admin' => 0
                                      ])
                                      ->pluck('key')
                                      ->toArray();

      }


      $problemsets = Problemset::all();

      $problemsetStudentScores = array();

      foreach ($problemsets as $problemset)
      {
        $problemsetStudentScores[$problemset->name] = array();
        foreach ($studentKeysInClasses as $keysInClass)
        {
          foreach ($keysInClass as $studentKey)
          {
            $score = \DB::table('problemsets_scores')
                          ->where([
                            ['problemset_name', '=', $problemset->name],
                            ['student_key', '=', $studentKey]
                          ])
                          ->pluck('score')
                          ->first();

            $problemsetStudentScores[$problemset->name][$studentKey] = is_null($score) ? 0 : $score;

          }
        }
      }


      return view('admin.index', [
        'instructor' => $instructor,
        'classes' => $classes,
        'studentKeysInClasses' => $studentKeysInClasses,
        'problemsets' => $problemsets,
        'problemsetStudentScores' => $problemsetStudentScores
      ]);
    }


    public function changeNames()
    {
      if (!Auth::check() || !Auth::user()->admin === 1)
      {
        return redirect('/admin/login');
      }

      $instructor = User::where('key', Auth::user()->key)->first();

      $classes = Section::where('instructor_id', $instructor->id)->get();

      $studentKeysInClasses = array();


      foreach ($classes as $class) {
        $studentKeysInClasses[$class->course_code] =
                                  $class->students();

      }

      return view('admin.changeNames', [
        'classes' => $classes,
        'studentKeysInClasses' => $studentKeysInClasses
      ]);

    }


}
