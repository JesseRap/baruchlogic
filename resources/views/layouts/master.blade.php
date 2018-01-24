<!DOCTYPE html>
<html lang="en">
  <head>
    <title>baruchlogic</title>

    @include('layouts/head')




  </head>
  <body>


    @include('/layouts/header')

    @yield('content')

    @include('/layouts/footer')



  </body>
</html>
