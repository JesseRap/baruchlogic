$(document).ready( ()=> {
  // Align the text based on the width of the divs,
  // since this varies between browsers
  window.setTimeout( ()=> {
    $('.textRow__centerText > span:nth-child(1)').each( (idx, obj) => {
      console.log(obj);
      ( ()=> {
        $(obj).ready( (idx, el) => {

        })
      })();
      $(obj).css('left', '-' + $(obj).width().toString() + 'px'  );
    });

    // Move the text to the right of 'baruchlogic'
    $('.home__textRow:nth-child(3) .textRow__centerText > span:nth-child(3)')
        .css('left', $('.textRow__mainText').width().toString() + 'px');
  }, 50);


});
