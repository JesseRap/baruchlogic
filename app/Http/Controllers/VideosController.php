<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function index()
    {

      $allVideos = \DB::table('videos')->get();

      $numVideos = \DB::table('videos')->groupBy('unit')->distinct()->count();


      return view('videos/index', [
        'allVideos' => $allVideos,
        'numUnits' => $numVideos,
        'currentVideo' => NULL
      ]);
    }

    public function video($shortTitle)
    {
      $currentVideo = \DB::table('videos')->where('short_title', '=', $shortTitle)->get();

      $numVideos = \DB::table('videos')->groupBy('unit')->distinct()->count();

      dd($currentVideo);

      return view('videos/index', [
        'allVideos' => $allVideos,
        'numUnits' => $numVideos,
        'currentVideo' => $currentVideo
      ]);

    }
}
