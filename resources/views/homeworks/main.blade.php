<script type="text/javascript" src="{{asset('/js/naturalDeduction.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/truthTable.js')}}"></script>


@if ($currentProblemset == NULL)

@else
  <div class="main problemset">


    <h3 class="problemset__name">
      {{$currentProblemset.problemset_name_long}}
    </h3>

    <div class="problemset__text">
      {{$currentProblemset.text}}
    </div>

    <?php $include_truth_table_logic = false; ?>

    @foreach ($currentProblemsetProblems as $problem)

      @if (problem.problem_type == 'truthtable')
        <?php $include_truth_table_logic = true; ?>
      @endif

      <div class="problem problem--2">

        <h4>1</h4>

        @include('/layouts/problemTemplates/$problem.problem_type.php')
      </div>


    @endforeach

    <button class="problemset__button" type="button" name="checkAnswerButton">Check Answers</button>


    <div class="problemset__score">
      YOUR SCORE IS: <span class="problemset__scoreSpan">50</span>
    </div>

  </div>


  @if ($include_truth_table_logic)

  <script type="text/javascript" src="{{asset('/js/truth-table-exercise-logic.js')}}"></script>

  @endif

}
@endif
