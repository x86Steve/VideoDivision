@extends('layouts.app')

@section('content')
    {{-- videodivision.net/assets/images/thumbnails/placeHolder.jpg--}}

    <br>
    <br>
    <img src="http://videodivision.net/assets/images/thumbnails/<?php echo ($file)[0]->Video_ID?>.jpg"  width= "240" height= "360"
         alt = "Place Holder" title="Production Title"
        align = "left">

    <h2>
        <br>
        &nbsp;&nbsp; <strong>Title:</strong>  <?php echo ($file)[0]->Title?>
    </h2>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>Rating: </strong> <?php echo ($file)[0]->Current_Rating ?></h5>
    <h2>
        &nbsp;&nbsp; <strong>Year:</strong>  <?php echo ($file)[0]->Year?> <br> <br>
        &nbsp;&nbsp; <strong>Subscription:</strong> <?php echo ($file)[0]->Subscription?> <br> <br> </h2>


        @if ($isMovie === 1)

            <h3><strong>&nbsp;&nbsp;&nbsp;&nbsp;Length: </strong><?php echo $extra->Length?> </h3>

        @else
            <h4><strong>&nbsp;&nbsp;&nbsp;&nbsp;Number of Seasons:</strong> <?php echo $extra->Season_Number?></h4>
            <h4><strong>&nbsp;&nbsp;&nbsp;&nbsp;Number of Episodes:</strong> <?php echo $extra->Episode_Number?></h4>
        @endif
        <br>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    //BUTTON TO ROUTE TO VIDEO PLAYER ######################################
    //EDIT THIS TO WHATEVER YOU WANT #######################################
    <a href="{{ route('video_details', $isMovie) }}">
        <button type="submit" class="btn btn-dark">Watch Now!</button>
    </a>

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


@endsection





