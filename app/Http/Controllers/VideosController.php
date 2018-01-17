<?php

namespace App\Http\Controllers;

use App\Task;

class VideosController extends Controller
{
    public function index()
    {

      $allVideos = \DB::table('videos')->get();

      $numVideos = \DB::table('videos')->count();

      echo $numVideos;

      return view('videos/index', [
        'allVideos' => $allVideos,
        'numUnits' => 4
      ]);
    }
}
