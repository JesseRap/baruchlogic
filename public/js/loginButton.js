$('.loginButton').click( ()=> {
  $('.loginButton__modal').removeClass('loginButton__modal--hidden');
});

$(document).mouseup( (event) => {
  let loginButton = $('.loginButton');
  let modal = $('.loginButton__modal');

  if (!loginButton.is(event.target) && loginButton.has(event.target).length === 0 &&
      !modal.is(event.target) && modal.has(event.target).length === 0) {
    console.log('OK');
    $('.loginButton__modal').addClass('loginButton__modal--hidden');
  }
});

// $('.loginButton').mouseout( ()=> {
//   $('.loginButton__modal').addClass('loginButton__modal--hidden');
// });
