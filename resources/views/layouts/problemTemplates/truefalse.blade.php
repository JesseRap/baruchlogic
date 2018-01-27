<div class="truefalse__container">
  {!!$problem->prompt!!}



  <form>

    <fieldset>

      <legend>True/False Question</legend>


        <input type="radio" class="js-response" name="truefalse"
          id="{{ $problem->id }}--true" value="T" data-answer="T">
        <label for="{{ $problem->id }}--true">True</label>

        <input type="radio" class="js-response" name="truefalse"
          id="{{ $problem->id }}--false" value="F" data-answer="F">
        <label for="{{ $problem->id }}--false">False</label>


    </fieldset>

  </form>



</div>
