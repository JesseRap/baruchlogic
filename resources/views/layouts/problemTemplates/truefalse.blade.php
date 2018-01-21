<div class="truefalse--container">
  {!!$problem->prompt!!}


<div class="">
  <input type="radio" class="js-response" name="{{ $problem->id }}" value="T" data-answer="T">
  <label for="contactChoice1">True</label>

  <input type="radio" class="js-response" name="{{ $problem->id }}" value="F" data-answer="F">
  <label for="contactChoice2">False</label>
</div>


</div>
