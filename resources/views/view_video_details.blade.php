@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-11 order-lg-1">
        {{--WARNING, THIS CODE IS CANCER--}}

        {{--Display video thumbnail on page--}}
        <br>
        <br>
        <img src="http://videodivision.net/assets/images/thumbnails/<?php echo ($file)[0]->Video_ID?>.jpg" width="240"
             height="360"
             alt="Place Holder" title="Production Title"
             align="left">

        {{--BASIC INFO (TITLE, RATING, YEAR, SUBSCRIPTION--}}
        <h2>
            <br>
            &nbsp;&nbsp; <strong>Title:</strong> <?php echo ($file)[0]->Title?>
        </h2>
        <h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Rating: </strong> <?php echo ($file)[0]->Current_Rating ?></h5>
        <h2>
            &nbsp;&nbsp; <strong>Year:</strong> <?php echo ($file)[0]->Year?> <br> <br>
            &nbsp;&nbsp; <strong>Subscription:</strong> <?php echo ($file)[0]->Subscription?> <br> <br></h2>

        {{--IF MOVIE DISPLAY LENGTH, IF EPISODE DISPLAY AMOUNT OF EPISODES--}}
        @if ($isMovie === 1)

            <h3><strong>&nbsp;&nbsp;&nbsp;&nbsp;Length: </strong><?php echo $extra->Length?> </h3>

        @else
            <h4><strong>&nbsp;&nbsp;&nbsp;&nbsp;Number of Seasons:</strong> <?php echo $extra->Season_Number?></h4>
            <h4><strong>&nbsp;&nbsp;&nbsp;&nbsp;Number of Episodes:</strong> <?php echo $num_of_episodes?></h4>
        @endif
        <br>

        {{--A BUNCH OF SPACES FOR FORMATTING (Screw using align)--}}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


        {{--IF USER HAS NOT PAID, ASK THEM TO PAY--}}
        @if ($isPaid == false)

            <button
                type="button"
                class="btn btn-dark"
                data-toggle="modal"
                data-target="#subscribeModal">
                Watch Now!
            </button>

            <div class="modal fade" id="subscribeModal"
                 tabindex="-1" role="dialog"
                 aria-labelledby="subscribeModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"
                                id="subscribeModalLabel">Almost there!</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                Subscribe to Video Division to instantly get access!
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-dark"
                                    data-dismiss="modal">Cancel
                            </button>
                            <span class="pull-right">

                    <a class="btn btn-dark" href="/public/payment">Pay now!</a>
            </span>
                        </div>
                    </div>
                </div>
            </div>

        @else

            {{--IF THE USER IS SUBBED GIVE ACCESS TO WATCH NOW BUTTON--}}
            @if($isSubbed === true)
                @if ($isMovie == 1)

                    <a href="/public/watch/<?php echo ($file)[0]->Video_ID?>">
                        <button type="submit" class="btn btn-dark">Watch Now!</button>
                    </a>
                @else
                    <a href="/public/view/<?php echo ($file)[0]->Video_ID?>">
                        <button type="submit" class="btn btn-dark">Watch Now!</button>
                    </a>
                @endif

                {{--OTHERWISE GIVE ACCESS TO SUBSCRIBE BUTTON--}}
            @else
                <button
                        type="button"
                        class="btn btn-dark"
                        data-toggle="modal"
                        data-target="#subscribeModal">
                    @if ($isMovie == 1) Rent! @else Subscribe! @endif
                </button>
                {{--IF USER IS LOGGED IN LET THEM SUBSCRIBE--}}
                @if($User_ID != -1)
                    <div class="modal fade" id="subscribeModal"
                         tabindex="-1" role="dialog"
                         aria-labelledby="subscribeModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"
                                        id="subscribeModalLabel">@if ($isMovie == 1) Rent? @else Subscribe? @endif </h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Please confirm you would like to @if ($isMovie == 1) rent @else subscribe to @endif
                                        <b><span id="sub-title"><?php echo ($file)[0]->Title?></span></b>
                                        .
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
                                    <button type="submit" form="form" class="btn btn-dark">@if ($isMovie == 1) Rent @else Subscribe @endif</button>
                                    <form id="form" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="User_ID" name="User_ID" value="<?php echo $User_ID?>">
                                        <input type="hidden" id="Video_ID" name="Video_ID" value="<?php echo ($file)[0]->Video_ID?>">
                                        <input type="hidden" id="isMovie" name="isMovie" value="<?php echo $isMovie?>">
                                        <input type="hidden" id="postType" name="postType" value="0">
                                    </form>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--IF USER IS NOT LOGGED IN ASK THEM TO LOGIN TO SUBSCRIBE--}}
                @else
                    <div class="modal fade" id="subscribeModal"
                         tabindex="-1" role="dialog"
                         aria-labelledby="subscribeModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"
                                        id="subscribeModalLabel">Please Login</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Please Login to subscribe to this video.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-dark"
                                            data-dismiss="modal">Cancel
                                    </button>
                                    <span class="pull-right">

                            <a class="btn btn-dark" href="/public/login">Login</a>
                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endif

        @endif

        {{--A BUNCH OF SPACES FOR FORMATTING (Screw using align)--}}



        {{--THE FAVORITE BUTTON--}}

        @if ($isMovie == 1)
            <br><br>
        @endif
        @if($isFav === false)
            <form id="form" method="post">
                {{ csrf_field() }}
                <input type="hidden" id="User_ID" name="User_ID" value="<?php echo $User_ID?>">
                <input type="hidden" id="Video_ID" name="Video_ID" value="<?php echo ($file)[0]->Video_ID?>">
                <input type="hidden" id="isMovie" name="isMovie" value="<?php echo $isMovie?>">
                <input type="hidden" id="postType" name="postType" value="1">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-dark btn-sm">Favorite!</button>
            </form>
        @else
            <form id="form" method="post">
                {{ csrf_field() }}
                <input type="hidden" id="User_ID" name="User_ID" value="<?php echo $User_ID?>">
                <input type="hidden" id="Video_ID" name="Video_ID" value="<?php echo ($file)[0]->Video_ID?>">
                <input type="hidden" id="isMovie" name="isMovie" value="<?php echo $isMovie?>">
                <input type="hidden" id="postType" name="postType" value="2">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-dark btn-sm">Un-favorite</button>
            </form>
        @endif


        {{--OTHER BASIC INFO (SUMMARY, GENRES, CAST, DIRECTORS)--}}
        <br>
        <br>
        <br>
        <h3><strong>Summary:</strong> <?php echo ($file)[0]->Summary?> <br> <br></h3>

        <div class="container">
            <div class="row">
                <div class="col-sm">

                    <h3><strong>Genre(s):</strong></h3>
                    @foreach($genres as $genre)
                        <h4>{{$genre['Name']}}&nbsp;&nbsp;</h4>
                    @endforeach
                </div>
                <div class="col-sm">
                    <h3><strong>Actor(s):</strong></h3>
                    @foreach($cast as $actor)
                        <h4> {{$actor['First_Name']}} {{$actor['Last_Name']}}</h4>
                    @endforeach
                </div>
                <div class="col-sm">
                    <h3><strong>Director(s):</strong></h3>
                    @foreach($directors as $director)
                        <h4> {{$director['First_Name']}} {{$director['Last_Name']}} </h4>
                    @endforeach

                </div>
            </div>
        </div>
            </div>
        </div>
    </div>


@endsection




