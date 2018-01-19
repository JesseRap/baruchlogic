
<div class="sidebar">

  <input type="checkbox" class="sidebar__checkbox" id="accordionBtn" name="accordionBtn" value="">

  <label class="sidebar__accordionBtn" for="accordionBtn">
  </label>


  <?php $num_units = \DB::table('videos')->select('unit')->distinct()->count() ?>

  <div class="sidebar__container">


  @foreach (range(1, $num_units) as $unit)

  <div class="sidebar__unit">

  <h4 >UNIT {{$unit}}</h4>

  @foreach ($allVideos as $key => $video)
    @if ($video->unit == $unit)

    <div class="sidebar__content">

      <div class="sidebar__circleContainer">
        <div class="circle circle--unwatched">
            </div>
          </div>

          <div>
            <div class="nowrap sidebar__itemNum">
              {{$key + 1}}
            </div>
          </div>

          <div class="sidebar__link">
            <a href="/videos/{{$video->short_title}}">{{$video->title}}</a>
          </div>


        </div>

        @endif
      @endforeach
    </div>


    @endforeach

</div>
</div>
