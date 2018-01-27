<div class="naturalDeduction__prompt">
  <div class="naturalDeduction__prompt__text">

  </div>
</div>

<div class="naturalDeduction__table__container">
  <table class="naturalDeduction__table">



  </table>
</div>


<div class="naturalDeduction__input__container">
  <div>
    Add a line to the proof:
  </div>
  <div class="naturalDeduction__input__div">
    <div>
      Proposition:
    </div>
    <input id="input--proposition"
      class="js-naturalDeduction__input input__proposition" type="text"
      name="input--proposition" value="" required>
  </div>
  <!-- <input id="input--justification" class="naturalDeduction--input input--justification" type="text" name="input--justification" value="Premise" required> -->

  <div class="naturalDeduction__input__div">
    <div>
      Justification:
    </div>
    <select type="select" id="justification--select"
      class="js-naturalDeduction__input naturalDeduction__input--select">
      <option selected disabled hidden>Select Justification</option>
      <option value="Premise" disabled>Premise</option>
      <option value="Addition">Addition</option>
      <option value="Conjunction">Conjunction</option>
      <option value="Contrapositive">Contrapositive</option>
      <option value="Double Negation">Double Negation</option>
      <option value="Disjunctive Syllogism">Disjunctive Syllogism</option>
      <option value="Modus Ponens">Modus Ponens</option>
      <option value="Simplification">Simplification</option>
    </select>
  </div>


  <div class="naturalDeduction__input__div">
    <div>
      Cited lines:
    </div>
    <input id="input--lines" class="js-naturalDeduction__input naturalDeduction__input--lines" type="text" name="input--lines" value="">
  </div>

  <button class="naturalDeduction__submit-btn" type="button" name="button">Submit</button>
</div>


<!-- Set up an instance of NaturalDeduction and bind event listeners -->
<script type="text/javascript">
  ( ()=> {
    const selector = '.problem--{{$loop->index}}';
    const nd = new NaturalDeduction(selector);
    // bind the submit button to the NaturalDeduction instance
    $(selector + ' button').click( ()=> {nd.onSubmit()});

    $(selector).click( (event)=> {

      if (event.target.classList.contains('x-span')) {
        const thisSpanIdx = $(selector + ' .x-span').index(event.target);
        nd.removeLastRow();
      };
    });

    x = '{{problem.data}}'
          .replace(/&quot;/g, '"')
          .replace(/&amp;/g, '&')
          .replace(/&gt;/, '>');
    const data = JSON.parse(x);
    nd.addPremises(data.premises);

    let promptHTML = '';
    data.premises.forEach( (obj, idx) => {
      promptHTML += (idx + 1) + '. ' + obj + '<br />';
    });
    promptHTML += '∴ ' + data.conclusion;
    $(selector + ' .naturalDeduction__prompt').html(promptHTML);
  })();


</script>
