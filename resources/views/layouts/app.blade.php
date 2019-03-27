<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VideoDivision') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            @include('inc.navbar')
            <div class="container">
                @if(Request::is('/'))
                    @include('inc.showcase')
                @endif
                <div class="row">
                    <div class="col-mid-8 col-lg-8">
                        <main class="py-4">
                            @yield('content')
                        </main>
                    </div>
                    <div class="col-mid-4 col-lg-4">
                        @include('inc.sidebar')
                    </div>
                </div>
            </div>
        </div>
        <footer id="footer" class="text-center">
            <p> Copyright 2019 &copy; VideoDivision.net</p>
        </footer>
    </body>
</html>
