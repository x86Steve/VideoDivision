
<div class="jumbotron jumbotron-fluid" xmlns="http://www.w3.org/1999/html">

<video autoplay muted loop poster="">
    <source src="" data-src="//clips.vorwaerts-gmbh.de/big_buck_bunny.mp4" type="video/mp4">
    <source src="" data-src="//clips.vorwaerts-gmbh.de/big_buck_bunny.webm" type="video/webm">
    <source src="" data-src="//clips.vorwaerts-gmbh.de/big_buck_bunny.ogv" type="video/ogg">
</video>

  <div class="container text-white">

<center>
    <h1 class="display-4">Welcome to VideoDivision.net</h1>
    <p class="lead">Say goodbye to Netflix , Hulu, and Crunchyroll!</p>
    <hr class="my-4">
    @if(Auth::check())
    <p>We can start you off by Logging in or, if you are new, Register now!</p>
    @endif

    @if(!Auth::check())
    <a class="btn btn-primary btn-lg" href="/public/login" role="button">Login</a>
    <a class="btn btn-primary btn-lg" href="/public/register" role="button">Register</a>
        @else
        <a class="btn btn-primary btn-lg" href="/public/profile" role="button">Welcome, {{Auth::user()->username}}</a>
        @endif
</center>

  </div>
  <!-- /.container -->
</div>
<!-- /.jumbotron -->


