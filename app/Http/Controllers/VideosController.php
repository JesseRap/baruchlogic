<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Video;

class VideosController extends Controller
{
    public function index($shortTitle = NULL)
    {

      // $allVideos = \DB::table('videos')->get();
      $allVideos = Video::all();

      $numUnits = \DB::table('videos')->groupBy('unit')->distinct()->count();

      if ($shortTitle !== NULL)
      {
        $currentVideo = \DB::table('videos')->where('short_title', '=', $shortTitle)->get()[0];
      }
      else {
        $currentVideo = NULL;
      }

      return view('videos/index', [
        'allVideos' => $allVideos,
        'numUnits' => $numUnits,
        'currentVideo' => $currentVideo
      ]);
    }


}
