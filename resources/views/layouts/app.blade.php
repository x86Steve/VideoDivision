 <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<link href="{{ asset('css/preview.css') }}" rel="stylesheet">



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
                    @yield('content')
            </div>
        </div>
        <footer id="footer" class="text-center">
            <p> Copyright 2019 &copy; VideoDivision.net</p>
        </footer>
    </body>

    <!-- This tells all data-feathers to initialize and create the icon you requested -->
    <script>feather.replace()</script>
</html>
