<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Exercise;

use App\Exerciseset;

use App\Problemset;

use App\User;

// use \DB;

class ExercisesController extends Controller
{
    public function index(Exerciseset $exerciseset)
    {

      $allExercisesets = Exerciseset::all();

      $numUnits = Exerciseset::select('unit')->distinct()->get()->count();

      $exercisesetExercises = $exerciseset->getExercises();

      $exercisesetsSolvedByUser = NULL;

      if (Auth::check())
      {
        $exercisesetsSolvedByUser =
          Exerciseset::join('exercisesets_scores',
                            'exercisesets.name',
                            '=',
                            'exercisesets_scores.exerciseset_name')
                            ->where([
                              ['student_key', '=', Auth::user()->key],
                              ['score', '=', 100]
                            ])
                            ->pluck('exerciseset_name')->toArray();
      }



      return view('exercises/index', [
        'allExercisesets' => $allExercisesets,
        'numUnits' => $numUnits,
        'exerciseset' => $exerciseset,
        'exercisesetExercises' => $exercisesetExercises,
        'exercisesetsSolvedByUser' => $exercisesetsSolvedByUser
      ]);
    }

    public function show($exercisesetName)
    {
      $exerciseset = Exerciseset::where('exercisesets.name', $exercisesetName)->first();

      return $this->index($exerciseset);

    }

    public function checkAnswers(Request $request) {


      $userAnswers = $request->input('userAnswers');
      $userAnswers = explode("|", $userAnswers);

      $type = $request->input('type');

      $exercisesetName = $request->input('currentExerciseset');
      $exerciseset;



      if ($type === 'exercises')
      {
        $exerciseset = Exerciseset::where('name', '=', $exercisesetName)->first();
      }
      else if ($type === 'homework')
      {
        $exerciseset = Problemset::where('name', '=', $exercisesetName)->first();
      }


      $correctAnswers = $exerciseset->getAnswers();


      $userGrades = array_map( function($el, $idx) use ($correctAnswers) {
        if (strlen($el) === 1) // The solution is selected-response
        {
          return $el === $correctAnswers[$idx] ? 1 : 0;
        }
        else if (preg_match('/^[TFX]+$/', $el)) // The solution is a truth table
        {
          return $el === $correctAnswers[$idx] ? 1 : 0;
        }
        // else // The problem is a proof
        // {
        //   // Build a new proof from the encoded proof, then check if valid
        //   $proof = new Proof;
        //   $proof->buildProofFromEncodedProof($el);
        //   return $proof->checkValidProof($proof->proof, $correctAnswers[$idx]) ? 1 : 0;
        // }
      }, $userAnswers, array_keys($userAnswers));



      $percentCorrect = floor((count(array_filter($userGrades, function($el)
        {
          return $el === 1;
        })) / count($correctAnswers)) * 100);




      if (Auth::check())
      {
        if ($type === 'exercises')
        {
          $recordQuery = \DB::table('exercisesets_scores')
                        ->where([
                          ['exerciseset_name', '=', $exercisesetName],
                          ['student_key', '=', Auth::user()->key]
                        ]);
        }
        else if ($type === 'homework')
        {
          $recordQuery = \DB::table('problemsets_scores')
                        ->where([
                          ['problemset_name', '=', $exercisesetName],
                          ['student_key', '=', Auth::user()->key]
                        ]);
        }

        $record = $recordQuery->first();

        if (!is_null($record))
        {
          if ($percentCorrect > $recordQuery->select('score')->first()->score) {
            $recordQuery->update(['score' => $percentCorrect]);
          }
        }
        else
        {
          if ($type === 'exercises')
          {
            \DB::table('exercisesets_scores')->insert([
              'student_key' => Auth::user()->key,
              'score' => $percentCorrect,
              'exerciseset_name' => $exercisesetName
            ]);
          }
          elseif ($type === 'homework')
          {
            \DB::table('problemsets_scores')->insert([
              'student_key' => Auth::user()->key,
              'score' => $percentCorrect,
              'problemset_name' => $exercisesetName
            ]);
          }
        }

      }



      echo $percentCorrect;
      return;

      // // Compare the user responses to the correct answer
      // $userGrades = array_map(function($el, $idx) use ($answerArrayFlat)
      //   {
      //     if (strlen($el) === 1) // The solution is stored in the db
      //     {
      //       return $el === $answerArrayFlat[$idx] ? "Y" : "N";
      //     }
      //     else // The problem is a proof - solution will be checked
      //     {
      //       // Build a new proof from the encoded proof, then check if valid
      //       require_once('../app/core/Classes.php');
      //       $proof = new Proof();
      //       $proof->buildProofFromEncodedProof($el);
      //       return $proof->checkValidProof($proof->proof, $answerArrayFlat[$idx]) ? "Y" : "N";
      //     }
      //   }, $userAnswers, array_keys($userAnswers));
      //
      // $percentCorrect = floor((count(array_filter($userGrades, function($el)
      //   {
      //     return $el === "Y";
      //   })) / count($userAnswers)) * 100);
      //
      // if (isset($_SESSION['key']))
      // // The first digit will mark whether the user is signed in
      // {
      //   echo '1' . $percentCorrect;
      // }
      // else
      // {
      //   echo '0' . $percentCorrect;
      // }
      //
      //
      // if ($percentCorrect == 100)
      // {
      //   $this->model->addProblemsetSolved($current_problemset);
      // }
      //
      // // STORE THE USER'S BEST SCORE ???
      //
      // return (string) $percentCorrect;
    }


}
