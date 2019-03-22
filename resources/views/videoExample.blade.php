@extends('layouts.app')

@section('content')

    <h1>Video Example</h1>
    <p>
        <video id="1" class="video-js vjs-default-skin vjs-big-play-centered"
               controls preload="auto">

            <source src="   {{($video)}}" type="video/mp4"/>
        </video>
        This is a simple video player on the website.
    </p>
@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>
