<div class="sidebar">

  <input type="checkbox" class="sidebar__checkbox" id="accordionBtn" name="accordionBtn" value="">

  <label class="sidebar__accordionBtn" for="accordionBtn">
  </label>


  @foreach (range(1, $numUnits) as $unit)

  <div class="sidebar__unit">

  <h4 >UNIT {{$unit}}</h4>

  @foreach ($allVideos as $key => $video)
    @if ($video->unit == $unit)

    <div class="sidebar__container">

      <div class="sidebar__circleContainer">
        <div class="circle circle--unwatched">
            </div>
          </div>

          <div>
            <div class="nowrap sidebar__itemNum">
              {{$key}}
            </div>
          </div>

          <div class="sidebar__link">
            <a href="/videos/video/{{$video->short_title}}">{{$video->title}}</a>
          </div>


        </div>

        @endif
      @endforeach


      @endforeach
