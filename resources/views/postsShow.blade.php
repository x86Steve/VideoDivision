
@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/css/star-rating.min.css" />

<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script>


@section('content')

    <?php
    $txt=0;
    foreach($user_id as $user_id)
    {
        if ($user_id ==Auth::user()->id)
        {
            $txt=1;
        }
    }
    ?>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="{{ route('posts.post') }}" method="POST">
                            {{ csrf_field() }}

                                <div class="container-fliud">
                                    <div class="wrapper row">
                                        <div class="preview col-md-6">
                                            <div class="preview-pic tab-content">
                                                <div class="tab-pane active" id="pic-1"><img src="http://videodivision.net/assets/images/thumbnails/{{ $post->id }}.jpg" />   </div>
                                            </div>
                                        </div>
                                        <div class="details col-md-6">
                                            <h3 class="product-title">{{ $post->name }}</h3>




                                                <?php echo "<br>";?>
                                                @if($txt==0)
                                                    <div class="rating">
                                                        <input id="input-1" name="rate" class="rating rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $post->userAverageRating }}" data-size="xs">
                                                        <input type="hidden" name="id" required="" value="{{ $post->id }}">
                                                        <input type="hidden" name="movieID" required="" value="{{ $post->id }}">
                                                        <input type="hidden" name="userID" required="" value="{{ Auth::user()->id }}">
                                                        <br/>
                                                    </div>
                                                    <p class="movie-description">{{ $post->Summary }}</p>
                                                    <div class="form-group">
                                                        <label for="message">First Review</label>
                                                    <textarea name="message" class="form-control" id="message" required placeholder="Please enter your review." cols="20" rows="5"></textarea>
                                                    <?php echo "<br>";?>
                                                <button class="btn btn-success">Submit Review</button>




                                                @elseif($txt==1)
                                                            <div class="rating">
                                                                <input id="input-1" name="rerate" class="rating rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $post->userAverageRating }}" data-size="xs">
                                                                <input type="hidden" name="id" required="" value="{{ $post->id }}">
                                                                <input type="hidden" name="movieID" required="" value="{{ $post->id }}">
                                                                <input type="hidden" name="userID" required="" value="{{ Auth::user()->id }}">
                                                                <br/>
                                                            </div>
                                                            <p class="movie-description">{{ $post->Summary }}</p>
                                                            <div class="form-group">
                                                                <label for="message">Second Review</label>
                                                    <textarea name="remessage" class="form-control" id="message" required placeholder="Please enter your new review." cols="20" rows="5"></textarea>
                                                    <?php echo "<br>";?>
                                                    <button class="btn btn-success">Change Review</button>
                                                @endif

                                                <?php echo "<br>";?>
                                                <?php echo "<br>";?>
                                                <label for="message">Latest Reviews for this Movie</label>
                                                <?php echo "<br>";?>
                                                <?php echo "<br>";?>
                                                @foreach($full_query as $review)


                                                    <div class="card">
                                                        <div class="card-body">

                                                            <img src="{{Config::get('customfilelocations.locations.avatars')}}{{ isset($CurrentUser) ? $CurrentUser->avatar : $review->avatar}}" onerror="this.src= '{{Config::get('customfilelocations.locations.avatars')}}default.png'" style="width: 100px; height: 100px; float:left; border-radius: 100%;margin-right: 2%;">

                                                            <h5> {{$review->username}}<?php echo "<br>";?><?php echo "<br>";?> <small>{{$review->review}}</small><?php echo "<br>";?>
                                                                <?php echo "<br>";?>
                                                                <small>{{$review->created_at}}</small>
                                                                <?php echo "<br>";?>

                                                            </h5>
                                                        </div>
                                                    </div>

                                                    @endforeach


                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>


                    </div>

                </div>

            </div>

        </div>

    </div>



    <script type="text/javascript">

        $("#input-id").rating();

    </script>

@endsection