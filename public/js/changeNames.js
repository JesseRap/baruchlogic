$('.button--changeNames').click( ()=> {
  const rows = $('tr:not(:first)');
  for (let i = 0; i < rows.length; i++) {
    const cells = $(rows[i]).find('td');
    const studentKey = $(cells[0]).text();
    console.log(studentKey);
    console.log($(cells[1]));
    const name = $(cells[1]).find('input').val();
    console.log(name);
    if (name !== '') {
      localStorage.setItem(studentKey, name);
    }
  }
})

function displayNames() {
  const rows = $('.table--class tr:not(:first)');
  for (let i = 0; i < rows.length; i++) {
    const cells = $(rows[i]).find('td');
    const studentKey = $(cells[0]).text();
    if (localStorage[studentKey]) {
      $(cells[0]).text(localStorage[studentKey]);
    }
  }
}

$( ()=> {
  displayNames();
})
