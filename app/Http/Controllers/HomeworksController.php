<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeworksController extends Controller
{
    public function index($problemsetName = NULL)
    {

      $allProblemsets = \DB::table('hw_problemsets')->get();

      $numUnits = \DB::table('hw_problemsets')->select('unit')->distinct()->count();

      if ($problemsetName !== NULL)
      {
        $currentProblemset = \DB::table('hw_problemsets')
                              ->find($problemsetName)->get();
        $currentProblemsetProblems = \DB::table('hw_problemsets_to_problems')
                                      ->where('problemset_name', $problemsetName)->get();
      }
      else
      {
        $currentProblemsetProblems = NULL;
        $currentProblemset = NULL;
      }



      return view('homeworks/index', [
        'allProblemsets' => $allProblemsets,
        'numUnits' => $numUnits,
        'currentProblemset' => $currentProblemset,
        '$currentProblemsetProblems' => $currentProblemsetProblems
      ]);
    }


}
