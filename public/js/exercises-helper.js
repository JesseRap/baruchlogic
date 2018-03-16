/**
 * Functions to process and submit user responses to the exercises
 */

( ()=> { // IIFE

/**
 * Retrieve the current problemset from the window URL
 * @return {string} The name of the current problemset
 */
function getCurrentProblemSet() {
  return window.location.href.match(/\/[^\/]+$/ig)[0].slice(1);
}

/**
 * Get the user's answers and post them to checkAnswers
 * @return {void} Makes POST request with user data
 */
function getAnswers() {


  // Create pipe-separated string of user answers
  let userAnswers = '';
  $('.truefalse__container, .truthTable, .multichoice__container').each( (idx, el) => {

    if ($(el).hasClass('truefalse__container')) { // TRUE/FALSE
      const response = $(el).find('.js-response:checked');
      if (response.length) {
        userAnswers += response[0].value + '|';
      } else {
        userAnswers += 'X|'; // Empty response is marked with an 'X'
      }

    } else if ($(el).hasClass('truthTable')) { // TRUTH TABLE
      const cells = $(el).find('.js-response-cell');
      $(cells).each( (i, cell) => {
        userAnswers += cell.innerHTML ? cell.innerHTML : 'X';
      });
      userAnswers += '|';

    } else if ($(el).hasClass('multichoice__container')) { // MULTIPLE CHOICE
      const response = $(el).find('.js-response:checked');
      if (response.length) {
        userAnswers += response[0].value + '|';
      } else {
        userAnswers += 'X|'; // Empty response is marked with an 'X'
      }
    }
  });
  // remove trailing pipe
  userAnswers = userAnswers.slice(0,userAnswers.length-1);


  const currentExerciseset = getCurrentProblemSet();
  var userIsLoggedIn = false;

  $.ajax({
    method: 'GET',
    url: '/authCheck',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done( (msg) => {
    userIsLoggedIn = msg === "TRUE" ? true : false;
  });

  const type = window.location.href.split('/')[3];

  // /exercises/checkAnswers is used to check exercises and problemsets
  // so the type must be passed along in the request data

  submitAnswers(currentExerciseset, userAnswers, type, userIsLoggedIn);

}

$('.problemset__button').click( ()=> {
  getAnswers();
});


/**
 * Submits a POST request to grade the user's answers
 */
function submitAnswers(currentExerciseset, userAnswers, type, userIsLoggedIn) {
  $.ajax({
    method: 'POST',
    url: '/exercises/checkAnswers',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {currentExerciseset, userAnswers, type},
  }).done( (msg) => {

    displayScore(msg, userIsLoggedIn);

    if (msg === '100') {

      if (userIsLoggedIn) {
        // ISSUE: Filling in the circle for score of 100 is confusing for UI
        // fillInTheCircle(currentExerciseset);
      }
      alert("Congratulations! You solved the problem set.")
    }
  }).fail( (msg) => {
    alert('Uh oh... Something went wrong. Try your request again.');
  });
}

/**
 * Fill in the circle for watched videos - DEPRECATED
 */
function fillInTheCircle(videoName) {
  $('.sidebar__content[data-problemset-name=' + videoName + '] .circle').removeClass('circle--unwatched').addClass('circle--watched');
}

/**
 * Display the user's score to the score area
 * @param {string} score The user's score
 * @return {void} Displays the score on the DOM
 */
function displayScore(score, userIsLoggedIn = false) {
  $('.problemset__scoreSpan').html(score + '%');
  if (userIsLoggedIn) {
    // THIS SEEMS FRAGILE
    let currentTopScore =
      $('.problemset__topScore').html().match(/\s\d+(?=%)/)[0].slice(1);
    if (score > currentTopScore) {
      $('.problemset__topScore').html(`Your top score: ${score}%`);
    }
  }
}

/**
 * Read the DOM from a deduction table and get all the user's input,
 * then encode it into string format
 * @param {DOMelement} tableElementDOM The table for the proof
 * @return {string} The string-encoded version of the proof
 */
function getEncodedProofFromDOM(tableElementDOM) {
  result = '';
  $(tableElementDOM).find('tr:not(:first-child)').each( (idx, tr) => {
    const TDs = $(tr).find('td');
    const justification = $(TDs[2]).text();
    const citedLines = $(TDs[3]).text();
    const formula = $(TDs[1]).text().replace(/\s/g, '');
    result += justification + '|' + citedLines + '|' + formula;
    if (idx < $(tableElementDOM).find('tr:not(:first-child)').length - 1) {
      result += '!';
    }
  });
  return result;
}


/**
 * Fill in the user response fields with the previously submitted responses
 * @param {string} userAnswers String containing the user's responses (T/F)
 * @return {void} Affects the layout
 */
function repopulateUserAnswers(userAnswers) {
  $('.js-response-cell, .truefalse--container').each( (idx, el) => {
    if (userAnswers[idx] === 'X') { // user left this field blank, so skip
      return;
    }
    if ($(el).hasClass('.js-response-cell')) {
      el.innerHTML = userAnswers[idx];
    } else if ($(el).hasClass('truefalse--container')) {
      const i = userAnswers[idx] === 'T' ? 0 : 1;
      $($(el).find('input')[i]).prop('checked', true);
    }
  });
}


})();
