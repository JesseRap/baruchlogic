<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeworksController extends Controller
{
    public function index()
    {

      $allProblemsets = \DB::table('hw_problemsets')->get();

      $numUnits = \DB::table('hw_problemsets')->select('unit')->distinct()->count();



      return view('homeworks/index', [
        'allProblemsets' => $allProblemsets,
        'numUnits' => $numUnits,
      ]);
    }


}
