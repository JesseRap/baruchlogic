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
  console.log('GET ANSWERS');


  // Create pipe-separated string of user answers
  let userAnswers = '';
  $('.truefalse__container, .truthTable').each( (idx, el) => {
    console.log(el);
    if ($(el).hasClass('truefalse__container')) {
      let response = $(el).find('.js-response:checked')
      console.log("!");
      if (response.length) {
        console.log("RESPONSE", response);
        userAnswers += response[0].value + '|';
      } else {
        userAnswers += 'X|'; // Empty response is marked with an 'X'
      }
    } else if ($(el).hasClass('truthTable')) {
      console.log("YUP");
      const cells = $(el).find('.js-response-cell');
      $(cells).each( (i, cell) => {
        console.log(cell, cell.innerHTML);
        userAnswers += cell.innerHTML ? cell.innerHTML : 'X';
      });
      userAnswers += '|';
    }
  });
  // remove trailing pipe
  userAnswers = userAnswers.slice(0,userAnswers.length-1);

  console.log(userAnswers);


  // $('.js-response-cell, .truefalse--container, .naturalDeduction__table').each(
  // (idx, el) => {
  //   console.log(idx, el);
  //   if ($(el).hasClass('js-response-cell')) {
  //     console.log('CELL--EMPTY', el.innerHTML, $(el).html());
  //     userAnswers += $(el).html().length > 0 ? $(el).html() : 'X';
  //   } else if ($(el).hasClass('truefalse--container')) {
  //     const checkedInput = $(el).find('input:checked');
  //     if (checkedInput.length) {
  //       userAnswers += checkedInput.val();
  //     } else {
  //       userAnswers += 'X';
  //     }
  //   } else if ($(el).hasClass('naturalDeduction__table')) {
  //     console.log('DEDUCTION');
  //     userAnswers += getEncodedProofFromDOM(el);
  //   }
  //   if (idx < $('.js-response-cell, .truefalse--container').length - 1) {
  //     // FIND A BETTER WAY TO DELIMIT ANSWERS
  //     userAnswers += '+';
  //   }
  // });
  // // const userAnswers = Array.from($('.js-response-cell,
  //                               // input[type="radio"]:checked'))
  // //                           .map( (o) => $(o).data('answer') ).join('');
  // console.log('userAnswers', userAnswers);
  const currentExerciseset = getCurrentProblemSet();
  console.log(currentExerciseset);

  $.ajax({
    method: 'POST',
    url: '/exercises/checkAnswers',
    beforeSend: () => {
      alert('sending POST');
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {currentExerciseset, userAnswers},
  }).done( (msg) => {
    console.log('POST is back ', msg);
    // alert(msg);
    // const isLoggedIn = msg[0];
    // msg = msg.slice(1);
    displayScore(msg);

    let userIsLoggedIn;
    $.ajax({
      method: 'GET',
      url: '/authCheck',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done( (msg) => {
      userIsLoggedIn = msg === "TRUE" ? true : false;
    });

    if (msg === '100') {
      if (userIsLoggedIn) {
        fillInTheCircle(currentExerciseset);
      }
      alert("Congratulations! You solved the problem set.")
    }
  }).fail( (msg) => {
    alert('Uh oh... Something went wrong. Try your request again.');
  });
};

$('.problemset__button').click( ()=> {
  getAnswers();
});

function fillInTheCircle(videoName) {
  console.log("FILL IN THE CIRCLE", videoName);
  $('.sidebar__content[data-problemset-name=' + videoName + '] .circle').removeClass('circle--unwatched').addClass('circle--watched');
}

/**
 * Display the user's score to the score area
 * @param {string} score The user's score
 * @return {void} Displays the score on the DOM
 */
function displayScore(score) {
  $('.problemset__scoreSpan').html(score + '%');
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
    console.log(TDs);
    const justification = $(TDs[2]).text();
    const citedLines = $(TDs[3]).text();
    const formula = $(TDs[1]).text().replace(/\s/g, '');
    console.log(formula);
    result += justification + '|' + citedLines + '|' + formula;
    if (idx < $(tableElementDOM).find('tr:not(:first-child)').length - 1) {
      result += '!';
    }
  });
  return result;
}

/**
 * POST back to the just-solved exercise page, with the user's response
 * included as POST data
 * @param {string} problemset Name of the problemset just responded to
 * @param {string} incorrectUserResponses String of 'Y/N' chars showing
 * user correct/incorrect answers
 * @return {void} Intialiates a POST request
 */
// function reviewAnswers(problemset, userAnswers, userResponsesYesNo) {
//   console.log('REVIEW ANSWERS', problemset, userAnswers, userResponsesYesNo);
//   const correctAnswers =
        // userResponsesYesNo.replace(new RegExp('N', 'g'), '');
//   const percentCorrect =
//     Math.floor((correctAnswers.length / userAnswers.length) * 100);
//   console.log(correctAnswers, percentCorrect);
//   post('/PHI1600b/public/exercises/exercise/' + problemset,
//     {problemset, userAnswers, userResponsesYesNo, percentCorrect});
// }


/**
 * Fill in the user response fields with the previously submitted responses
 * @param {string} userAnswers String containing the user's responses (T/F)
 * @return {void} Affects the layout
 */
function repopulateUserAnswers(userAnswers) {
  console.log('REPOPULATE', userAnswers);
  $('.js-response-cell, .truefalse--container').each( (idx, el) => {
    if (userAnswers[idx] === 'X') { // user left this field blank, so skip
      return;
    }
    console.log((idx, el));
    console.log($(el).hasClass('truefalse--container'));
    if ($(el).hasClass('.js-response-cell')) {
      el.innerHTML = userAnswers[idx];
    } else if ($(el).hasClass('truefalse--container')) {
      const i = userAnswers[idx] === 'T' ? 0 : 1;
      console.log($(el).find('input')[i]);
      $($(el).find('input')[i]).prop('checked', true);
    }
  });
}


})();
