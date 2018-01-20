<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>baruchlogic</title>

    @include('layouts/head')




  </head>
  <body>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('/layouts/header')

    @yield('content')

    @include('/layouts/footer')



  </body>
</html>
