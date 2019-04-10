@extends('layouts.app')
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/css/star-rating.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet" type="text/css" />

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-default">



                    <div class="panel-body">

                        <form action="{{ route('posts.post') }}" method="POST">

                            {{ csrf_field() }}

                            <div class="card">

                                <div class="container-fliud">

                                    <div class="wrapper row">

                                        <div class="preview col-md-6">



                                            <div class="preview-pic tab-content">

                                                <div class="tab-pane active" id="pic-1"><img src="http://videodivision.net/assets/images/thumbnails/{{ $post->id }}.jpg" />   </div>

                                            </div>



                                        </div>

                                        <div class="details col-md-6">

                                            <h3 class="product-title">{{ $post->name }}</h3>

                                            <div class="rating">

                                                <input id="input-1" name="rate" class="rating rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $post->userAverageRating }}" data-size="xs">

                                                <input type="hidden" name="id" required="" value="{{ $post->id }}">


                                                <br/>

                                                <button class="btn btn-success">Submit Review</button>

                                            </div>
                                                <p class="movie-description">{{ $post->Summary }}</p>
                                            <div class="form-group">
                                                <label for="message">Your Review</label>
                                                <textarea name="message" class="form-control" id="message" required placeholder="Please enter your review." cols="20" rows="5">
        </textarea>
<?php echo "<br>";?>
                                                <label for="message">Latest Reviews for this Movie</label>
                                               <?php
                                                foreach ($review as $review) {
                                                    echo "<br>";
                                                    echo "<br>";
                                                echo $review;
                                                }

                                                ?>


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