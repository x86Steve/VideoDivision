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

        <h1>Upload video</h1>

        <br>

        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <h2>General information</h2>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mediatype" id="moviechoice" onclick="changeMediaType()" checked="true" value="movie" required>
                    <label class="form-check-label" for="moviechoice">Movie</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mediatype" id="showchoice" onclick="changeMediaType()" value="show" >
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

            <div id="actors">
                <h2>Actors</h2>
                <div class="form-group">
                    <label for="actorSelect1">Choose existing actor</label>
                    <select class="form-control" id="actorSelect1" name="actorSelect1" required>
                        @foreach ($actors as $actor)
                        <option label="{{$actor->First_Name}} {{$actor->Last_Name}}" value="{{$actor->Actor_ID}}" >
                            @endforeach
                    </select>
                    <div class="form-check">
                        <input class="form-check-input" name="addNew1" id="addNew1" type="checkbox" onchange="toggleActorNameFields()" >
                        <label class="form-check-label" for="addNew1">Add new actor to database</label>
                    </div>
                    <div class="body form-row" hidden>
                        <label for="actorInputFirst1">Enter name</label>
                        <input class="form-control form-control-inline" id="actorInputFirst1" name="actorInputFirst1">
                        <input class="form-control" id="actorInputLast1" name="actorInputLast1">
                    </div>
                </div>

            </div>

            <div class="form-group btn-group">
                <button class="btn btn-primary" type="button" onclick="addActor()">+</button>
                <button class="btn btn-danger" id="removeButton" type="button" onclick="removeActor()" disabled>-</button>
            </div>
            <!--All submissions contain a movie or episode-->
            <!--<div id="nonShowFields">-->
            <!--</div>-->

            <h2>File</h2>            

            <div class="form-group">
                <input type="file" class="form-control-file" name="video" id="video" onchange="initPlayer()" required>
            </div>
            <div id="videopreview" hidden>
                <video 
                    id="player" 
                    class="video-js vjs-default-skin vjs-big-play-centered"
                    width="730"
                    onloadedmetadata="loadDuration()" 
                    controls 
                    />
                <div class="form-group">
                    <label for="duration">Runtime:</label>
                    <input type="text" class="form-control" id="duration" name="duration" readonly>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>


        </form>

        <script src='https://vjs.zencdn.net/7.4.1/video.js'></script>

        <br>

        @if ($status === 1)
        <h2>Upload success</h2>
        {{-- @elseif ($newShowId != "-1")
        <h2>Created new show with ID {{serialize($newShowId)}}</h2> --}}
    @elseif ($status === 2)
    <h2>Upload failed</h2>
    @endif       

        @endsection

        <script>

                    // this is called when the user changes what type of video
                    // is being uploaded
                    function changeMediaType()
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

                    // this is called when the user checks or unchecks
                    // an "add new actor" checkbox
                    function toggleActorNameFields()
                    {
                        var actors = document.querySelectorAll('#actors > .form-group');
                        var i;
                        for (i = 0; i < actors.length; i++)
                        {
                            var checked = actors[i].querySelector('.form-check-input').checked;
                            actors[i].querySelector('.body').hidden = !checked;
                            actors[i].querySelector('#actorSelect' + (i + 1)).disabled = checked;
                        }
                    }

                    // this is called when the user clicks the plus button
                    // to add an actor
                    function addActor()
                    {
                        var newCount = document.querySelectorAll('#actors > .form-group').length + 1;
                        var firstActor = document.querySelector('#actors > .form-group');
                        var newActor = firstActor.cloneNode(true);
                        newActor.querySelector('label').htmlFor = "actorSelect" + newCount;
                        //alert(newActor.querySelector('label'))
                        newActor.querySelector('select').id = "actorSelect" + newCount;
                        newActor.querySelector('select').name = "actorSelect" + newCount;
                        newActor.querySelector('select').disabled = false;
                        newActor.querySelector('.form-check-input').id = "addNew" + newCount;
                        newActor.querySelector('.form-check-input').name = "addNew" + newCount;
                        newActor.querySelector('.form-check-input').checked = false;
                        newActor.querySelector('.form-check-label').htmlFor = "addNew" + newCount;
                        
                        newActor.querySelector('.body > label').htmlFor = "actorInputFirst" + newCount;
                        newActor.querySelector('.body > input').id = "actorInputFirst" + newCount;
                        newActor.querySelector('.body > input').name = "actorInputFirst" + newCount;
                        newActor.querySelector('.body > input').value = "";
                        //newActor.querySelector('.body > label').htmlFor = "actorInputFirst" + newCount;
                        newActor.querySelectorAll('.body > input')[1].id = "actorInputLast" + newCount;
                        newActor.querySelectorAll('.body > input')[1].name = "actorInputLast" + newCount;
                        newActor.querySelectorAll('.body > input')[1].value = "";

                        newActor.querySelector('.body').hidden = true;
                        document.getElementById("actors").appendChild(newActor);
                        document.getElementById("removeButton").disabled = false;
                    }

                    // this is called when the user clicks the minus button
                    // to remove an actor
                    function removeActor()
                    {
                        var lastElement = document.getElementById("actors").lastElementChild;
                        document.getElementById("actors").removeChild(lastElement);
                        var count = document.querySelectorAll('#actors > .form-group').length;
                        if (count == 1)
                            document.getElementById("removeButton").disabled = true;
                    }

                    // this is called when the user changes the
                    // value of the file input (chooses a new file)
                    function initPlayer()
                    {
                        var video = document.getElementById("video");
                        if (video.files.length > 0)
                        {
                            document.getElementById("videopreview").hidden = false;
                            var player = videojs('player');

                            // when the player is ready, load the new
                            // video information into the videojs object
                            player.ready(function ()
                            {
                                var fileType = video.files[0].type;
                                var url = URL.createObjectURL(video.files[0]);
                                this.src({type: fileType, src: url});
                            });
                        } else
                        {
                            document.getElementById("videopreview").hidden = true;
                        }
                    }

                    // this is called once the videojs player has finished
                    // loading metadata for the video
                    function loadDuration()
                    {
                        var player = videojs('player');
                        var fullDuration = videojs.formatTime(player.duration());
                        document.getElementById("duration").setAttribute("value", fullDuration);
                    }

                    </script>

                    </body>
                    </html>
