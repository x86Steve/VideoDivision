<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="/">VideoDivision</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Request::segment(1) === 'contact' ? 'active' : null }}">
                <a class="nav-link" href="/public/contact">Contact</a>
            </li>
            <li class="nav-item {{ Request::segment(1) === 'about' ? 'active' : null }}">
                <a class="nav-link" href="/public/about">About</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Videos</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="/public/live_search/grid">Grid View</a>
                    <a class="dropdown-item" href="/public/live_search/table">Table View</a>
                    <a class="dropdown-item" href="/public/posts">Video Ratings</a>
                </div>
            </li>
            <li class="nav-item {{ Request::segment(1) === 'my_videos' ? 'active' : null }}">
                <a class="nav-link" href="/public/my_videos">My Videos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Administrator-Login</a>
            </li>
            @if(Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hey, {{ Auth::user()->name }}!</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="/public/profile">Edit Profile</a>
                    <a class="dropdown-item" href="#">Edit Subscription</a>
                    <a class="dropdown-item" href="#">Watch your shows!</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                </div>
            </li>
            @endif
            @guest
                <li class="nav-item {{ Request::segment(1) === 'register' ? 'active' : null }}">
                    <a class="nav-link" href="/public/register">Register</a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'login' ? 'active' : null }}">
                    <a class="nav-link" href="/public/login">Login</a>
                </li>
            @endguest
        </ul>
        <!---
            DILAPIDATED BUTTON KEEP UNTIL FURTHER NOTICE######################################
            <form class="form-inline my-2 my-lg-0" action="\{\{ action('Search\SearchController@basicSearch') }}" method="GET" role="search">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="q">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>
        --->
    </div>
</nav>

