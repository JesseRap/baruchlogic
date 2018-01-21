
  <div class="loginButton__modal header__modal loginButton__modal--hidden">
    @if (Auth::check())
      <div class="">
        Welcome, Unique Human #{{Auth::user()->nonce}}
      </div>
      <div class="">
        <a href="/session/logout">Log Out</a>
      </div>
    @else
      <a href="/login">Sign In</a>
    @endif
  </div>
