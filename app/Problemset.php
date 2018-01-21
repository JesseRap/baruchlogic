<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problemset extends Model
{
  public function getProblems()
  {

    $problems = Problem::join('problemsets_to_problems',
                                'problems.id',
                                '=',
                                'problemsets_to_problems.problem_id')
                                ->where('problemset_name', $this->name)
                                ->get();

    foreach($problems as $problem)
    {
      $problem->choices_decoded = json_decode($problem->choices, true);
    }

    return $problems;

  }


  public function getAnswers()
  {
    $problems = $this->getProblems();

    $answers = $problems->pluck('answers');

    return $answers;

  }
}
