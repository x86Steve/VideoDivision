@extends('layouts.app')

@section('content')

    <h1 style="font-size:60px;"><span class="bigger"> <?php echo ($file)[0]->Title?></span></h1>

    @if ($isMovie == 0)
        <p>
            <strong>S<?php echo $extra->Season_Number?>: E<?php echo $episode_number?> </strong>
            <?php echo $episode_title?>
        </p>
    @endif
    <body>
    <video id="1" autoplay class="video-js" width='768' height='432'
           controls preload="metadata"
           poster="http://videodivision.net/assets/images/thumbnails/<?php echo ($file)[0]->Video_ID?>.jpg">

        <source src="http://videodivision.net{{($file_path)}}" type="video/mp4"/>
        <p class='vjs-no-js'>
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>
        </p>

    </video>
    </body>


    @if ($isMovie == 0)
        <p>

        <div class="btn-group" role="group" aria-label="...">
            @if ($episode_number != 1)
                <a class="nav-link"
                   href="/watch/ <?php echo ($file)[0]->Video_ID?>/episode/<?php echo $episode_number - 1?>">
                    <button type="submit" class="btn btn-dark btn-lg active btn-block"><span aria-hidden="true" align="right"></span> Previous
                        Episode
                    </button>
                </a>
            @endif
            <!--<a class = "nav-link" href="insert episode list link here" > <span aria-hidden="true"></span>
            <button type="submit" class="btn btn-dark btn-lg active btn-block">All Episodes</button>
            </a>-->
            <a class="nav-link"
               href="/watch/<?php echo ($file)[0]->Video_ID?>  /episode/<?php echo $episode_number + 1?>"><span
                        aria-hidden="true"></span>
                <button type="submit" class="btn btn-dark btn-lg active btn-block">Next Episode</button>
            </a>
        </div>
        </p>
    @endif




@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>
