<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Upload - VideoDivision</title>
        <link href="https://vjs.zencdn.net/7.4.1/video-js.css" rel="stylesheet">

        <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
        <script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>
    </head>
    <body>
        @extends('layouts.app')

        @section('content')

        <script>
            function updateForm()
            {
                var type = document.querySelector('input[name="mediatype"]:checked').value;

                var i;
                var episodeElements = document.getElementById("episodeFields").querySelectorAll("input");
                for (i = 0; i < episodeElements.length; i++)
                {
                    episodeElements[i].disabled = (type === "show") ? false : true;
                }

                document.getElementById("episodeFields").hidden = (type === "show") ? false : true;
            }

            function initPlayer()
            {
                //var options = {};
                var video = document.getElementById("video");
                var url = "";
                if (video.files.length > 0)
                {
                    url = URL.createObjectURL(video.files[0]);
                    var player = videojs('player');
                    player.ready(function ()
                    {
                        var fileType = video.files[0].type;
                        this.src({type: fileType, src: url});
                    });
                } else
                {
                    document.getElementById("duration").setAttribute("value", "");
                    //source.setAttribute('src', "");
                }
            }

            function loadDuration()
            {
                var player = videojs('player');
                var date = new Date(null);
                date.setSeconds(player.duration());
                var fullDuration = videojs.formatTime(player.duration());
                document.getElementById("duration").setAttribute("value", fullDuration);
            }
        </script>

        <h1>Upload video</h1>

        <br>

        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mediatype" id="moviechoice" onclick="updateForm()" checked="true" value="movie" required>
                    <label class="form-check-label" for="moviechoice">Movie</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mediatype" id="showchoice" onclick="updateForm()" value="show" >
                    <label class="form-check-label" for="showchoice">Show</label>
                </div>
            </div>

<!--<input type="radio" name="mediatype" id="episodechoice" onclick="updateForm()" value="episode" > Episode--> 

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Video Title" required>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" class="form-control" name="year" id="year" minlength="4" maxlength="4" placeholder="XXXX" required>
            </div>

            <div id="episodeFields" hidden>
                <!--Show ID shouldn't be required-->
                <!--<input type="number" class="form-control" name="showId" id="showId" placeholder="Show ID" required disabled="true" > -->
                <div class="form-group">
                    <label for="seasonNumber">Season Number</label>
                    <input type="number" class="form-control" name="seasonNumber" id="seasonNumber" placeholder="#" required disabled="true">
                </div>
                <div class="form-group">
                    <label for="episodeNumber">Episode Number</label>
                    <input type="number" class="form-control" name="episodeNumber" id="episodeNumber" placeholder="#" required disabled="true">
                </div>
            </div>

            <div class="form-group">
                <label for="summary">Summary</label>
                <input type="text" class="form-control" name="summary" id="summary" placeholder="Summary text..." required>
            </div>

            <!--Maybe make this read only if adding episode to existing show?-->
            <!--<div id="movieFields">-->
            <!--</div>-->
            <div class="form-group">
                <label for="subscription">Subscription</label>
                <input type="text" class="form-control" name="subscription" id="subscription" placeholder="Subscription" required>
            </div>

            <!--All submissions contain a movie or episode-->
            <!--<div id="nonShowFields">-->
            <!--</div>-->

            <div class="form-group">
                <label for="duration">Runtime:</label>
                <input type="text" class="form-control" id="duration" name="duration" readonly>
            </div>
            <div class="form-group">
                <input type="file" class="form-control-file" name="video" id="video" onchange="initPlayer()" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

            <video 
                id="player" 
                class="video-js vjs-default-skin" 
                onloadedmetadata="loadDuration()" 
                controls 
                >
                <!--<source id="source" src="//vjs.zencdn.net/v/oceans.mp4" type="video/mp4"/>-->
            </video>

        </form>

        <script src='https://vjs.zencdn.net/7.4.1/video.js'></script>

        <br>

        @if ($status === 1 && $newShowId == "-1")
        <h2>Upload success</h2>
        @elseif ($newShowId != "-1")
        <h2>Created new show with ID {{serialize($newShowId)}}</h2>
        @elseif ($status === 2)
        <h2>Upload failed</h2>
        @endif       

        @endsection

    </body>
</html>
