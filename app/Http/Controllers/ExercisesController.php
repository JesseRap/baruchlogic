<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Exercise;

use App\Exerciseset;

use App\User;

class ExercisesController extends Controller
{
    public function index(Exerciseset $exerciseset)
    {

      $allExercisesets = Exerciseset::all();

      $numUnits = Exerciseset::select('unit')->distinct()->get()->count();

      $exercisesetExercises = $exerciseset->getExercises();


      return view('exercises/index', [
        'allExercisesets' => $allExercisesets,
        'numUnits' => $numUnits,
        'exerciseset' => $exerciseset,
        'exercisesetExercises' => $exercisesetExercises
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

      $exercisesetName = $request->input('currentExerciseset');
      $exerciseset = Exerciseset::where('name', '=', $exercisesetName)->first();

      $correctAnswers = $exerciseset->getAnswers();

      // print_r($userAnswers);
      // return;

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
        // $recordQuery = DB::table('exercisesets_scores')
        //               ->where('exerciseset_name', '=', $exercisesetName)
        //               ->where('student_key', '=', $this->key);
        //
        // $record = $recordQuery->first();
        //
        // if ($record) {
        //   if ($percentCorrect > $recordQuery->select('score')->first()) {
        //     $record->update(['score' => $percentCorrect]);
        //   }
        // } else {
        //   DB::table('exercisesets_scores')->insert([
        //     'student_key' => $this->key,
        //     'score' => $percentCorrect,
        //     'exerciseset_name' => $exercisesetName
        //   ]);
        // }
      }



      echo $percentCorrect;

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
