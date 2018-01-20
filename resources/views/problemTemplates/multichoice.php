<div class="multichoice__container">


  {% autoescape %}
  {{problem.prompt | raw}}
  {% endautoescape %}


  <div class="multichoice__choices">

  {% for letter, choice in problem.choices_decoded %}

    <span class="nowrap">

      <input type="radio" class="multichoice__input"
        name="{{problem.problem_id}}" value="{{letter}}"
        data-answer="{{letter}}">

      <label class="multichoice__label" for="{{problem.problem_id}}">
        {{choice}}
      </label>

    </span>

  {% endfor %}

  </div>


</div>
