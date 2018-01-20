// Event handlers for dealing with user input on the truth tables

( () => {

  let clickedCellIdx = -1;

  $('.js-response-cell').on('mouseenter', (event)=> {
    event.target.classList.add('cell--active');
  });

  $('.js-response-cell').on('mouseleave', (event)=> {
    event.target.classList.remove('cell--active');
  });


  window.addEventListener('click', (event)=> {
    clearClickedCells();
    if ($(event.target).hasClass('js-response-cell')) {
      $(event.target).addClass('cell--clicked');
      clickedCellIdx = Array.from($('.js-response-cell'))
                            .indexOf($('.cell--clicked')[0]);
      console.log(clickedCellIdx);
    } else {
      clickedCellIdx = -1;
      console.log(clickedCellIdx);
    };
  });

  window.addEventListener('keydown', (event)=> {
    console.log(event);
    console.log(clickedCellIdx);
    let numOfCells = $('.js-response-cell').length;
    if (['t', 'T', 'f', 'F'].indexOf(event.key) > -1) {
      $('.cell--clicked').html(event.key.toUpperCase());
      $($('.cell--clicked')[0]).data('answer', event.key.toUpperCase());
      if (clickedCellIdx === numOfCells) {
        clearClickedCells();
        clickedCellIdx = -1;
      } else if (clickedCellIdx > -1) {
        clickedCellIdx += 1;
        clearClickedCells();
        $('.js-response-cell')[clickedCellIdx].classList.add('cell--clicked');
      }
    } else if (event.key === 'ArrowUp') {
      if (clickedCellIdx > 0) {
        clickedCellIdx -= 1;
        clearClickedCells();
        $('.js-response-cell')[clickedCellIdx].classList.add('cell--clicked');
      }
    } else if (event.key === 'ArrowDown') {
      if (-1< clickedCellIdx && clickedCellIdx + 1 < numOfCells) {
        clickedCellIdx += 1;
        clearClickedCells();
        $('.js-response-cell')[clickedCellIdx].classList.add('cell--clicked');
      } else {
        clearClickedCells();
        clickedCellIdx = -1;
      }
    } else if (event.key === 'Backspace') {
      $('.active .cell--clicked').html('');
      $($('.active .cell--clicked')[0]).data('answer', '');
    }
  });


  /**
   * Remove '.cell--clicked' from all '.js-response-cell'
   @return {void} Changes the truth table layout
   */
  function clearClickedCells() {
    $('.js-response-cell').removeClass('cell--clicked');
  }

})();
