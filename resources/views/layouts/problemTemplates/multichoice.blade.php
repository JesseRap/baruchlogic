<div class="multichoice__container">


  {!! $problem->prompt !!}

    <form>

      <fieldset>

        <legend>Multiple Choice Question</legend>

        @foreach ($problem->choices_decoded as $letter => $choice)

          <span class="nowrap">

            <input type="radio" class="multichoice__input js-response" name="multichoice"
              id="{{ $problem->id }}--{{ $letter }}" value="{{ $letter }}"
              data-answer="{{ $letter }}"
              @if (!Auth::check())
                disabled="true"
              @endif
              >

            <label class="multichoice__label"
              for="{{ $problem->id }}--{{ $letter }}">
              {{ $choice }}
            </label>

          </span>

        @endforeach


      </fieldset>

    </form>



</div>
