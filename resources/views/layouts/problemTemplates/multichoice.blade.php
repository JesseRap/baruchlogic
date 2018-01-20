<div class="multichoice__container">


  {!!$problem->prompt!!}


  <div class="multichoice__choices">

  @foreach ($problem->choices_decoded as $letter => $choice)

    <span class="nowrap">

      <input type="radio" class="multichoice__input js-response"
        name="{{$problem->problem_id}}" value="{{$letter}}"
        data-answer="{{$letter}}">

      <label class="multichoice__label" for="{{$problem->problem_id}}">
        {{$choice}}
      </label>

    </span>

  @endforeach

  </div>


</div>
