<div class="sidebar">

  <!-- BUTTON TO TOGGLE ACCORDION FOR MENU CONTENT -->
  <input type="checkbox" class="sidebar__checkbox" id="accordionBtn"
    name="accordionBtn" value="">

  <label class="sidebar__accordionBtn" for="accordionBtn" data-text="">
  </label>


  <div class="sidebar__container">

    @foreach (range(1, $numUnits) as $unit)

    <div class="sidebar__unit">

      <h4>UNIT {{$unit}}</h4>
      <?php $idx = 1; ?>

      @foreach ($allExercisesets as $exerciseset)
        @if ($exerciseset->unit === $unit)


          <div class="sidebar__content" data-problemset-name={{$exerciseset->name}}>

            <div class="sidebar__circleContainer">


                @if (in_array($exerciseset->name, $exercisesetsSolvedByUser))
                  <div class="circle circle--watched"></div>
                @else
                  <div class="circle circle--unwatched"></div>
                @endif

            </div>

            <div>
              <div class="nowrap sidebar__itemNum">
                {{$idx}}
                <?php $idx = $idx + 1; ?>
              </div>
            </div>


            <div class="sidebar__link">
              <a href="/exercises/{{$exerciseset->name}}"> {{$exerciseset->name_long}}</a>
            </div>


          </div>
          @endif

          @endforeach
    </div>

    @endforeach

  </div>





</div>
