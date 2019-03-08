    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>VideoDivision</title>
            <link rel = "stylesheet" href = "/css/app.css">
            <script src="/js/app.js"></script>
        </head>
        <body>
        @include('inc.navbar')

        <div class="container">
            @if(Request::is('/'))
                @include('inc.showcase')
            @endif
            <div class="row">
                <div class="col-mid-8 col-lg-8">
                    @yield('content')

                    @if(Request::is('login'))
                        @include('inc.loginform')
                    @endif

                </div>
                <div class="col-mid-4 col-lg-4">
                    @include('inc.sidebar')
                </div>
            </div>
        </div>

        <footer id="footer" class="text-center">
            <p> Copyright 2019 &copy; VideoDivision.net</p>
        </footer>
        </body>
    </html>