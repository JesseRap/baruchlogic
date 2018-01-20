$('.loginButton').mouseover( ()=> {
  $('.loginButton__modal').removeClass('loginButton__modal--hidden');
});

$('.loginButton').mouseout( ()=> {
  $('.loginButton__modal').addClass('loginButton__modal--hidden');
});
