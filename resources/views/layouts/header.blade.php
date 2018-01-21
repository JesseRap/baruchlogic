  <!-- HEADER -->

  <header class="header">

    <!-- MENU ICON -->



    <!-- NAVIGATION MENU -->

    <nav class="navMenu header__navMenu" aria-label="Main Navigation">

      <?php $url = url()->current(); ?>

      <div class="navMenu__item">

        @if ($url != "http://baruchlogic.test/home")
          <a class="navMenu__anchor" href="/home">Home</a>
        @else
        Home
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != "http://baruchlogic.test/about")
          <a class="navMenu__anchor" href="/about">About</a>
        @else
        About
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != "http://baruchlogic.test/videos")
          <a class="navMenu__anchor" href="/videos">Videos</a>
        @else
        Videos
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != "http://baruchlogic.test/exercises")
          <a class="navMenu__anchor" href="/exercises">Exercises</a>
        @else
        Exercises
        @endif

      </div>

      <div class="navMenu__item">

        @if ($url != "http://baruchlogic.test/Homework")
          <a class="navMenu__anchor" href="/homework">Homework</a>
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
          {{Auth::user()->key}}
        @else
          <a href="/login">Sign In</a>
        @endif
      </div>
    </div>

    <script type="text/javascript" src="{{asset('/js/loginButton.js')}}" defer></script>



  </header>
