<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E-Store')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
  </head>
  <body>
    @include('include.header')
    @yield('content')
    
  </body>
</html>