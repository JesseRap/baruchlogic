/* Change the names on the admin dashboard */

// Go through the input fields and update the names in localStorage
$('.button--changeNames').click( ()=> {
  const rows = $('tr:not(:first)');
  for (let i = 0; i < rows.length; i++) {
    const cells = $(rows[i]).find('td');
    const studentKey = $(cells[0]).text();
    const name = $(cells[1]).find('input').val();
    if (name !== '') {
      localStorage.setItem(studentKey, name);
    }
  }
})

// Display the names stored in localStorage
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

/* TODO: Add function to reset names to initial keys */
