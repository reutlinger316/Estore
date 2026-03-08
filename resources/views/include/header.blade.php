<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
    <ul class="navbar-nav">
      <li><a class="nav-link active" href="{{route('home')}}">Home</a></li>
      @auth
        <li><a class="nav-link" href="{{route('logout')}}">Logout</a></li>
      @else
        <li><a class="nav-link" href="{{route('login')}}">Login</a></li>
        <li><a class="nav-link" href="{{route('signin')}}">Signin</a></li>
      @endauth
    </ul>
  </div>
</nav>