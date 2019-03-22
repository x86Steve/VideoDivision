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

        <h1 align="center"><br>Search <br></h1><br />

            <br>
            <br>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search Shows and Movies" />
                    </div>
                    <div class="table-responsive">
                        <h3 align="center">Matching Videos : <span id="total_records"></span></h3>
                        <table class="table table-striped table-bordered">
                            <thead>
                            @if ($isGridView === 0)
                    <tr>
                        <th>Title</th>
                        <th>Year</th>
                        <th>Subscription</th>
                        <th>Rating</th>
                        <th>View Page</th>
                    </tr>
                            @endif
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <script>
                $(document).ready(function(){

                    fetch_video_data();

                    function fetch_video_data(query = '')
                    {
                        var url = $(location).attr('href');
                        var viewType = url.split('/').reverse()[0];

                        if(viewType === "grid")
                        {
                            $.ajax({

                                url:"{{ route('live_search.grid') }}",
                                method:'GET',
                                data:{query:query},
                                dataType:'json',
                                success:function(data)
                                {
                                    $('tbody').html(data.table_data);
                                    $('#total_records').text(data.total_data);
                                }
                            })
                        }
                        else
                        {
                            $.ajax({

                                url:"{{ route('live_search.table') }}",
                                method:'GET',
                                data:{query:query},
                                dataType:'json',
                                success:function(data)
                                {
                                    $('tbody').html(data.table_data);
                                    $('#total_records').text(data.total_data);
                                }
                            })
                        }

                    }

                    $(document).on('keyup', '#search', function(){
                        var query = $(this).val();
                        fetch_video_data(query);
                    });
                });
            </script>

            @if(Request::is('login'))
                @include('inc.loginform')
            @endif

    </div>

<footer id="footer" class="text-center">
    <p> Copyright 2019 &copy; VideoDivision.net</p>
</footer>
</body>
</html>



