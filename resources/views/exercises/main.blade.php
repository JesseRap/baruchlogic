<script type="text/javascript" src="{{asset('/js/naturalDeduction.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/truthTable.js')}}"></script>




<div class="main problemset">

@if ($currentProblemset !== NULL)

  <h3 class="problemset__name">
    {{$currentProblemset->problemset_name_long}}
  </h3>

  <div class="problemset__text">
    {{$currentProblemset->text}}
  </div>

  <?php $include_truth_table_logic = FALSE; ?>

  @foreach ($currentProblemsetProblems as $key => $problem)

    @if ($problem->problem_type === 'truthtable')
      <?php $include_truth_table_logic = TRUE; ?>
    @endif

    <div class="problem problem--{{$key + 1}}">

      <h4>({{$key + 1}})</h4>

      @include('/layouts/problemTemplates/' . $problem->problem_type)

    </div>


  @endforeach

  <button class="problemset__button" type="button" name="checkAnswerButton">Check Answers</button>

  @if ($include_truth_table_logic)
  <script type="text/javascript" src="{{asset('/js/truth-table-exercise-logic.js')}}"></script>
  @endif
  <div class="problemset__score">
    YOUR SCORE IS: <span class="problemset__scoreSpan">percentCorrect</span>
  </div>
  @endif

</div>
