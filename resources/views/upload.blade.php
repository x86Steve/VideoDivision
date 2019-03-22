<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Upload - VideoDivision</title>
    </head>
    <body>
        @extends('layouts.app')

        @section('content')

        <script>
            function updateForm()
            {
                //window.alert("updateForm()");
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
                var options = {};
                var source = document.getElementById("source");
                var video = document.getElementById("video");
                var url = window.URL.createObjectURL(video.files[0]);
                source.setAttribute('src', url);
                // var player = videojs('player', options, function onPlayerReady()
                // {
                //     this.play();
                //     window.alert("Duration: " + this.duration());
                // });
                //window.alert("waiting to initialize player");
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
                <label for="hours">Runtime:</label>
                <div class="form-row">
                    <div class="col">
                        <input type="number" class="form-control" name="hours" id="hours" min="0" max="10" placeholder="h" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="minutes" id="minutes" min="0" max="59" placeholder="mm" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="seconds" id="seconds" min="0" max="59" placeholder="ss" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="file" class="form-control-file" name="video" id="video" onchange="initPlayer()" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

            <video id="player" class="video-js vjs-default-skin vjs-big-play-centered" controls >
                <source id="source" type="video/mp4"/>
            </video>

        </form>


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
