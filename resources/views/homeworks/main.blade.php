<script type="text/javascript" src="{{asset('/js/naturalDeduction.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/truthTable.js')}}"></script>


@if ($problemset->exists)


  <div class="main problemset">

    <div class="problemset__topScore">
      Your top score:
        @if (is_null($problemsetTopScore))
          N/A
        @else
          {{$problemsetTopScore}}%
        @endif
    </div>

    <h3 class="problemset__name">
      {{$problemset->name_long}}
    </h3>

    <div class="problemset__text">
      {{$problemset->text}}
    </div>

    <?php $include_truth_table_logic = false; ?>

    @foreach ($problemsetProblems as $problem)

      @if ($problem->type == 'truthtable')
        <?php $include_truth_table_logic = true; ?>
      @endif

      <div class="problem">

        <h4>({{$loop->iteration}})</h4>

        @include('/layouts/problemTemplates/'. $problem->type)
      </div>


    @endforeach

    <button class="problemset__button" type="button" name="checkAnswerButton"
      @if (!Auth::check())
        disabled="true"
      @endif
    >Check Answers</button>


    <div class="problemset__score">
      YOUR SCORE IS: <span class="problemset__scoreSpan"></span>
    </div>

  </div>


  @if ($include_truth_table_logic)

  <script type="text/javascript" src="{{asset('/js/truth-table-exercise-logic.js')}}"></script>

  @endif


@endif
