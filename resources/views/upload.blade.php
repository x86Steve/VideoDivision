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
                //window.alert("called method");
                var type = document.querySelector('input[name="mediatype"]:checked').value;
                //window.alert(type);
                //var show = type === "show";
                //var episode = type === "episode";

                //document.getElementById("episodeFields").style.display = (type === "episode") ? "block" : "none";
                //document.getElementById("nonShowFields").style.display = (type === "show") ? "none" : "block";
                //document.getElementById("nonEpisodeFields").style.display = (type === "episode") ? "none" : "block";

                var i;
                var episodeElements = document.getElementById("episodeFields").querySelectorAll("input");
                for (i = 0; i < episodeElements.length; i++)
                {
                    //window.alert(episodeElements[i]);
                    episodeElements[i].disabled = (type === "show") ? false : true;
                }
                //window.alert("got here");

//                var nonShowElements = document.getElementById("nonShowFields").querySelectorAll("input");
//                for (i = 0; i < nonShowElements.length; i++)
//                {
//                    nonShowElements[i].disabled = (type === "show") ? true : false;
//                }
//
//                var nonEpisodeFields = document.getElementById("nonEpisodeFields").querySelectorAll("input");
//                for (i = 0; i < nonEpisodeFields.length; i++)
//                {
//                    nonEpisodeFields[i].disabled = (type === "episode") ? true : false;
//                }

//                if (type === "episode")
//                {
//                    document.getElementById("episodeFields").removeAttribute("disabled");
//                    document.getElementById("nonEpisodeFields").setAttribute("disabled", "disabled");
//                }
//                else
//                {
//                    document.getElementById("episodeFields").setAttribute("disabled", "disabled");
//                    document.getElementById("nonEpisodeFields").removeAttribute("disabled");
//                }
//                
//                if (type === "show")
//                {
//                    document.getElementById("nonShowFields").setAttribute("disabled", "disabled");
//                }
//                else
//                {
//                    document.getElementById("nonShowFields").removeAttribute("disabled");
//                }
                //document.getElementById("episodeFields").setAttribute("disabled", ) = (type === "episode") ? " : true;
                //document.getElementById("nonShowFields").disabled = (type === "show") ? true : false;

                document.getElementById("episodeFields").hidden = (type === "show") ? false : true;
//                document.getElementById("nonShowFields").hidden = (type === "show") ? true : false;
//                document.getElementById("nonEpisodeFields").hidden = (type === "episode") ? true : false;
            }
            function initPlayer()
            {
                var player = document.getElementById("player");
                var source = document.getElementById("source");
                source.src = window.URL.createObjectURL(input.file);
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
                <input type="file" class="form-control-file" name="video" id="video" onchange="initPlayer" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

            <video id="player" class="video-js vjs-default-skin vjs-big-play-centered"
                   controls >
                <source type="video/mp4"/>
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
