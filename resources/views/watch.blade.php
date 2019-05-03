@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-11 order-lg-1">
            <h1 style="font-size:60px;"><span class="bigger"> <?php echo ($file)[0]->Title?></span></h1>

                        @if ($isMovie == 0)
                            <p>
                                <strong>S<?php echo $season_number?>: E<?php echo $episode_number?> </strong>
                                <?php echo $episode_title?>
                            </p>
                        @endif
                        <body>
                        <video oncontextmenu="return false;" id="player" autoplay class="play" width='768' height='432'
                               controls controlsList="nodownload"
                               poster="http://videodivision.net/assets/images/thumbnails/<?php echo ($file)[0]->Video_ID?>.jpg">

                            <source src="http://videodivision.net{{($file_path)}}" type="video/mp4"/>
                            <p class='vjs-no-js'>
                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>
                            </p>
                        </video>

            {{ActivityEntry("Started watching: " . helper_GetMovieTitleByID(($file)[0]->Video_ID))}}

            </body>

            @if ($isMovie == 0)
                <p>
                <div class="btn-group" role="group">
                    @if ($is_special_episode == 3)
                        <a class="nav-link"
                           href="/public/watch/{{(($file)[0]->Video_ID)}}/season/{{($season_number - 1)}}/episode/{{($previous_episode)}}">
                            <button type="submit" class="btn btn-dark">Previous Episode</button>
                        </a>
                    @elseif ($is_special_episode == 0 || $is_special_episode == 1 || $is_special_episode == 2)
                        <a class="nav-link"
                           href="/public/watch/{{(($file)[0]->Video_ID)}}/season/{{($season_number)}}/episode/{{($episode_number - 1)}}">
                            <button type="submit" class="btn btn-dark">Previous Episode</button>
                        </a>
                    @endif
                        <a class="nav-link"
                                href="/public/view/<?php echo ($file)[0]->Video_ID?>">
                            <button type="submit" class="btn btn-dark">All Episodes</button>
                        </a>
                    @if ($is_special_episode == 0 || $is_special_episode == 3)
                        <a class="nav-link"
                           href="/public/watch/{{(($file)[0]->Video_ID)}}/season/{{($season_number)}}/episode/{{($episode_number + 1)}}">
                            <button type="submit" class="btn btn-dark">Next Episode</button>
                        </a>
                    @elseif ($is_special_episode == 1)
                        <a class="nav-link"
                           href="/public/watch/{{(($file)[0]->Video_ID)}}/season/{{($season_number + 1)}}/episode/{{(1)}}">
                            <button type="submit" class="btn btn-dark">Next Episode</button>
                        </a>
                    @elseif ($is_special_episode == 4)
                        <a class="nav-link"
                           href="/public/watch/{{(($file)[0]->Video_ID)}}/season/{{($season_number)}}/episode/{{($episode_number + 1)}}">
                            <button type="submit" class="btn btn-dark">Next Episode</button>
                        </a>
                </div>
            @endif

        </div>
        @endif
        </div>
        </div>
    </div>
@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>