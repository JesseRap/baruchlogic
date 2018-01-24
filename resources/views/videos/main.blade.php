<div class="main videos">


  @if ($currentVideo->exists)
  <h3 class="videos__name">
    {{ $currentVideo->title }}
  </h3>


  <iframe class="videos__iframe" width="560" height="315"
    src="{{$currentVideo->video_url}}" frameborder="0"
    allow="encrypted-media" allowfullscreen></iframe>

  @endif


</div>
