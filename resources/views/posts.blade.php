@extends('layouts.app')
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/css/star-rating.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet" type="text/css" />

@section('content')

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-default">

                    <div class="panel-heading">Ratings</div>



                    <div class="panel-body">



                        <table class="table table-bordered">

                            <tr>



                                <th>Movie</th>

                                <th width="220px">Average Rating</th>

                                <th width="100px">Details</th>

                            </tr>

                            @if($posts->count())

                                @foreach($posts as $post)

                                    <tr>


                                        <td>{{ $post->name }}</td>

                                        <td>

                                            <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="{{ $post->averageRating }}" data-size="xxxs" disabled="">

                                        </td>

                                        <td>

                                            <a href="{{ route('posts.show',$post->id) }}" class="btn btn-primary btn-sm">View</a>

                                        </td>

                                    </tr>

                                @endforeach

                            @endif

                        </table>



                    </div>

                </div>

            </div>

        </div>

    </div>



    <script type="text/javascript">

        $("#input-id").rating();

    </script>

@endsection