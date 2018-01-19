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

        @if ($url != "http://baruchlogic.test/Homeworks")
          <a class="navMenu__anchor" href="/homeworks">Homework</a>
        @else
        Homework
        @endif

      </div>


      <!-- LOGIN ICON & MODAL -->



    </nav>




    <!-- LOGIN ICON & MODAL -->

    <div class="loginButton header__loginButton">
          <img src="/images/user-icon.png" alt="login icon">
          <div class="loginButton__modal header__modal loginButton__modal--hidden">
            @if (session('key'))
              Welcome,john
            @elseif (session('adminkey'))
              Welcome, jane
            @else
              <a href="/login">Sign In</a>
            @endif
          </div>
        </div>



  </header>
