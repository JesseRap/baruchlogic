<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>baruchlogic</title>

    @include('layouts/head')




  </head>
  <body>

<main>

Welcome to the professor admin panel.

<a href="/session/logout">Log Out</a>


@foreach ($classes as $class)


  <div class="">
    Class Code: {{$class->course_code}}
    <a href="/admin/changeNames">Change Names</a>
  </div>

  <table class="table table-striped table--class">

    <thead>
      <tr>
        <th>Student Key</th>
        @foreach ($problemsets as $problemset)
        <th>{{$problemset->name_long}}</th>
        @endforeach
      </tr>
    </thead>

    @foreach ($studentKeysInClasses[$class->course_code] as $studentKey)
      <tr>
        <th>{{$studentKey}}</th>
        @foreach ($problemsets as $problemset)
          <td>{{$problemsetStudentScores[$problemset->name][$studentKey]}}</td>
        @endforeach
      </tr>
    @endforeach
  </table>

@endforeach

<script type="text/javascript">
function displayNames() {
  console.log("DISPLAY NAMES");
  const rows = $('tr:not(:first)');
  for (let i = 0; i < rows.length; i++) {
    const cells = $(rows[i]).find('th');
    const studentKey = $(cells[0]).text();
    console.log(studentKey);
    if (localStorage.getItem(studentKey)) {
      console.log("YES");
      $(cells[0]).text(localStorage.getItem(studentKey));
    }
  }
}

$( ()=> {
  displayNames();
})
</script>

</main>

</body>
