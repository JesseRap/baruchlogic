  <!-- HEADER -->

  <header class="header">

    <!-- MENU ICON -->

<!-- <div class="logo">
  baruchlogic
</div> -->

    <!-- NAVIGATION MENU -->

    <nav class="navMenu header__navMenu" aria-label="Main Navigation">

      <?php $url = url()->current(); ?>

      <div class="navMenu__item">

        @if ($url != route('home'))
          <a class="navMenu__anchor" href="{{ route('home') }}">Home</a>
        @else
        Home
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != route('about'))
          <a class="navMenu__anchor" href="{{ route('about') }}">About</a>
        @else
        About
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != route('videos'))
          <a class="navMenu__anchor" href="{{ route('videos') }}">Videos</a>
        @else
        Videos
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != route('exercises'))
          <a class="navMenu__anchor" href="{{ route('exercises') }}">Exercises</a>
        @else
        Exercises
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != route('homework'))
          <a class="navMenu__anchor" href="{{ route('homework') }}">Homework</a>
        @else
        Homework
        @endif

      </div>


      <!-- LOGIN ICON & MODAL -->



    </nav>


    <!-- LOGIN ICON & MODAL -->

    <div class="loginButton header__loginButton">
      <img src="{{asset('/images/user-icon.png')}}" alt="login icon">
      <div class="loginButton__modal header__modal loginButton__modal--hidden">
        @if (Auth::check())
          <div class="">
            Welcome, Unique Human #{{Auth::user()->key}}
          </div>
          <div class="">
            <a href="/session/logout">Log Out</a>
          </div>
        @else
          <a href="/login">Sign In</a>
        @endif
      </div>
    </div>

    <script type="text/javascript" src="{{asset('/js/loginButton.js')}}" defer></script>



  </header>
