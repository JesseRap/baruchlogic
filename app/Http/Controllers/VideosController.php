<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Video;

class VideosController extends Controller
{
    public function index(Video $video)
    {

      $allVideos = Video::all();

      $numUnits = Video::groupBy('unit')->distinct()->count();

      return view('videos/index', [
        'allVideos' => $allVideos,
        'numUnits' => $numUnits,
        'currentVideo' => $video
      ]);
    }

    public function show($shortTitle)
    {
      $video = Video::where('short_title', '=', $shortTitle)->first();
      return $this->index($video);
    }


}
