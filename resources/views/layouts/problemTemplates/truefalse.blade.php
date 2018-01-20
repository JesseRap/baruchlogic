<div class="truefalse--container">
  {!!$problem->prompt!!}


<div class="">
  <input type="radio"
   name="{{$problem->problem_id}}" value="T" data-answer="T">
  <label for="contactChoice1">True</label>

  <input type="radio"
   name="{{$problem->problem_id}}" value="F" data-answer="F">
  <label for="contactChoice2">False</label>
</div>


</div>
