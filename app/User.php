<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'remember_token',
    ];


    public function storeScore($percentCorrect, $exercisesetName)
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
      return "HELLO";
    }

}
