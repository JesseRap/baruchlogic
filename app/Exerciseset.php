<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exerciseset extends Model
{
    public function getExercises()
    {

      $exercises = Exercise::join('exercisesets_to_exercises',
                                  'exercises.id',
                                  '=',
                                  'exercisesets_to_exercises.exercise_id')
                                  ->where('exerciseset_name', $this->name)
                                  ->get();

      foreach($exercises as $exercise)
      {
        $exercise->choices_decoded = json_decode($exercise->choices, true);
        // $exercise->data = html_entity_decode($exercise->data);
      }
      // var_dump($exercises);

      return $exercises;

    }

    public function getAnswers()
    {
      $exercises = $this->getExercises();

      $answers = $exercises->pluck('answers');

      return $answers;


    }

}
