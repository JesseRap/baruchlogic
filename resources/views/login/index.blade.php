@extends('/layouts/master')

@section('content')

<main class="main login">

  <form id='login' class="loginForm" action='session' method='post' accept-charset='UTF-8'>

    {{ csrf_field() }}

    <h4>SIGN IN</h4>
    <input type='hidden' name='submitted' id='submitted' value='1'/>


    <div class="loginForm__fields">

      <span class="nowrap">
        <label for='key' >Key*:</label>
        <input type='password' name='key' id='key' maxlength="50"
            autocomplete="off" required/>
      </span>
    </div>


    <input class="loginForm__submit" type='submit' name='login_user' value='Submit' />


  </form>

</main>

@endsection('content')
