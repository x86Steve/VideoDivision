
<div class="jumbotron jumbotron-fluid" xmlns="http://www.w3.org/1999/html">
    <video autoplay muted loop poster="http://why.soserio.us/Media/chrome11556691513.png">
        <source src="http://why.soserio.us/Media/Untitled%20Project.mp4" type="video/mp4">
    </video>

    <div class="container text-white">
        <div class="center">
            <h1 class="display-5">Welcome to VideoDivision.net</h1>
            <p class="lead">Say goodbye to Netflix , Hulu, and Crunchyroll!</p>
            <hr class="my-4">
            @if(!Auth::check())
            <p>We can start you off by Logging in or, if you are new, Register now!</p>
            <a class="btn btn-primary btn-lg" href="/public/login" role="button">Login</a>
            <a class="btn btn-primary btn-lg" href="/public/register" role="button">Register</a>
            @else
            <a class="btn btn-primary btn-lg" href="/public/profile" role="button">Welcome, {{Auth::user()->username}}</a>
            @endif
        </div>
    </div>
</div>



