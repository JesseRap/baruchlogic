<div class="multichoice__container">


  {!! $problem->prompt !!}


  <div class="multichoice__choices">

    <form>

      <fieldset>

        <legend>Multiple Choice Question</legend>

        @foreach ($problem->choices_decoded as $letter => $choice)

          <span class="nowrap">

            <input type="radio" class="multichoice__input js-response" name="multichoice"
              id="{{ $problem->id }}--{{ $letter }}" value="{{ $letter }}"
              data-answer="{{ $letter }}">

            <label class="multichoice__label"
              for="{{ $problem->id }}--{{ $letter }}">
              {{ $choice }}
            </label>

          </span>

        @endforeach


      </fieldset>

    </form>

  </div>


</div>
