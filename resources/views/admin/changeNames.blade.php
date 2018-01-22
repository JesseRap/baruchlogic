@include('layouts/head')

Enter names for your students' keys.
These names are stored in your computer's cache, and will not be stored
if you clear the cache or use a different computer. Please do NOT use
this feature on a shared computer.
<br>
<a href="/admin">Back to Admin Panel</a>



@foreach ($classes as $class)


  <div class="">
    Class Code: {{$class->course_code}}
  </div>

  <table class="table table-striped table--changeNames">

    <thead>
      <tr>
        <th>Student Key</th>
        <th>Name</th>
      </tr>
    </thead>

    @foreach ($studentKeysInClasses[$class->course_code] as $studentKey)
      <tr>
        <td>{{$studentKey}}</td>
        <td><input type="text" name="" value=""></td>
      </tr>
    @endforeach
  </table>

@endforeach


<button type="submit" name="button" class="button--changeNames">Submit Names</button>

<script type="text/javascript" src="/js/changeNames.js"></script>
