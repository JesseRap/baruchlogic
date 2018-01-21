<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Problem;

use App\Problemset;

class HomeworksController extends Controller
{
    public function index(Problemset $problemset)
    {

      $allProblemsets = Problemset::all();

      $numUnits = Problemset::select('unit')->distinct()->get()->count();

      $problemsetProblems = $problemset->getProblems();


      if (Auth::check())
      {
        $problemsetsSolvedByUser =
          Problemset::join('problemsets_scores',
                            'problemsets.name',
                            '=',
                            'problemsets_scores.problemset_name')
                            ->where([
                              ['student_key', '=', Auth::user()->key],
                              ['score', '=', 100]
                            ])
                            ->pluck('problemset_name')->toArray();
      }


      return view('homeworks/index', [
        'allProblemsets' => $allProblemsets,
        'numUnits' => $numUnits,
        'problemset' => $problemset,
        'problemsetProblems' => $problemsetProblems,
        'problemsetsSolvedByUser' => $problemsetsSolvedByUser
      ]);

    }


    public function show($problemsetName)
    {
      $problemset = Problemset::where('problemsets.name', $problemsetName)->first();

      return $this->index($problemset);

    }



}
