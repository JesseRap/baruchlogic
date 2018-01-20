<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExercisesController extends Controller
{
    public function index($exerciseName = NULL)
    {

      $allProblemsets = \DB::table('problemsets')->get();

      $numUnits = \DB::table('problemsets')->select('unit')->distinct()->get()->count();


      if ($exerciseName !== NULL)
      {
        $currentProblemset = \DB::table('problemsets')
                              ->where('problemset_name', $exerciseName)->get()[0];

        // $currentProblemsetProblems = \DB::table('problemsets_to_problems_map')
        //                               ->where('problemset_name', $exerciseName)->get();
        $currentProblemsetProblems = \DB::table('problems')
                                          ->join('problemsets_to_problems_map', 'problems.problem_id', '=', 'problemsets_to_problems_map.problem_id')
                                          ->where('problemset_name', $exerciseName)->get();

      foreach($currentProblemsetProblems as $problem)
      {
        $problem->choices_decoded = json_decode($problem->choices, true);
      }

      }
      else
      {
        $currentProblemsetProblems = NULL;
        $currentProblemset = NULL;
      }


      return view('exercises/index', [
        'allProblemsets' => $allProblemsets,
        'numUnits' => $numUnits,
        'currentProblemset' => $currentProblemset,
        'currentProblemsetProblems' => $currentProblemsetProblems
      ]);
    }


}
