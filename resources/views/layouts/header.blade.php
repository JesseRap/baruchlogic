
<head>

  @include('layouts/head')


</head>



<body>

  <!-- HEADER -->

  <header class="header">

    <?php $url = url()->current(); ?>

    <!-- MENU ICON -->

    <!-- <div class="header__menuIcon">
      <img src="/resources/images/menu-icon.png" alt="">
    </div> -->


    <!-- NAVIGATION MENU -->

    <nav class="navMenu header__navMenu" aria-label="Main Navigation">

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

      </div>
    </div>



  </header>
</body>
