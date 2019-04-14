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

    <br>
    <h1>Upload video</h1>
    <br>

    <form id="form" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>General information</h2>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mediatype" id="moviechoice" onclick="changeMediaType()" checked="true"
                    value="movie" required>
                <label class="form-check-label" for="moviechoice">Movie</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mediatype" id="showchoice" onclick="changeMediaType()" value="show">
                <label class="form-check-label" for="showchoice">Show</label>
            </div>
        </div>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Movie Title" required>
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" class="form-control" name="year" id="year" minlength="4" maxlength="4" placeholder="XXXX" required>
        </div>
        <div class="form-group">
            <label for="summary">Summary</label>
            <input type="text" class="form-control" name="summary" id="summary" placeholder="Summary text..." required>
        </div>
        <div class="form-group">
            <label for="subscription">Subscription</label>
            <input type="text" class="form-control" name="subscription" id="subscription" placeholder="Subscription" required>
        </div>


        <div id="showFields" hidden>
            <h2>Show information</h2>
            <div class="form-group">
                <label for="showSelect">Show</label>
                <select class="form-control" id="showSelect" name="showSelect" required>
                    @foreach ($shows as $show)
                    <option label="{{$show->Title}}" value="{{$show->Video_ID}}">
                        @endforeach
                </select>
                <div class="form-check">
                    <input class="form-check-input" name="addNewShow" id="addNewShow" type="checkbox" onchange="toggleShowNameField()">
                    <label class="form-check-label" for="addNewShow">Add new show to database</label>
                </div>
                <div id="newShowFields" hidden>
                    <label for="showInput">Enter show name</label>
                    <input class="form-control" id="showInput" name="showInput" placeholder="Show Name" required disabled>                    {{-- <input class="form-control" id="actorInputLast1" name="actorInputLast1"> --}}
                    <label for="showSummary">Enter show summary</label>
                    <input class="form-control" id="showSummary" name="showSummary" placeholder="Summary text..." required disabled>
                </div>
                <label for="seasonNumber">Season Number</label>
                <input type="number" class="form-control" name="seasonNumber" id="seasonNumber" placeholder="#" required disabled>
                <label for="episodeNumber">Episode Number</label>
                <input type="number" class="form-control" name="episodeNumber" id="episodeNumber" placeholder="#" required disabled>
            </div>
        </div>


        <div id="actors">
            <h2>Actors</h2>
            <div class="form-group">
                <label for="actorSelect1">Select an existing actor</label>
                <select class="form-control" id="actorSelect1" name="actorSelect1" required>
                    @foreach ($actors as $actor)
                    <option label="{{$actor->First_Name}} {{$actor->Last_Name}}" value="{{$actor->Actor_ID}}">
                        @endforeach
                </select>
                <div class="form-check">
                    <input class="form-check-input" name="addNew1" id="addNew1" type="checkbox" onchange="toggleActorNameFields()">
                    <label class="form-check-label" for="addNew1">Enter new actor</label>
                </div>
                <div class="form-row" hidden>
                    {{-- <label for="actorInputFirst1">Enter name</label><br> --}}
                    <div class="col">
                        <input class="form-control" id="actorInputFirst1" name="actorInputFirst1" placeholder="First name" required disabled>
                    </div>
                    <div class="col">
                        <input class="form-control" id="actorInputLast1" name="actorInputLast1" placeholder="Last name" required disabled>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-group btn-group">
            <button class="btn btn-primary" type="button" onclick="addActor()">+ Add actor</button>
            <button class="btn btn-danger" id="removeButton" type="button" onclick="removeActor()" disabled>- Remove actor</button>
        </div>

        <div id="directors">
            <h2>Directors</h2>
            <div class="form-group">
                <label for="actorSelect1">Select an existing director</label>
                <select class="form-control" id="actorSelect1" name="actorSelect1" required>
                                @foreach ($directors as $director)
                                <option label="{{$director->First_Name}} {{$director->Last_Name}}" value="{{$director->Director_ID}}">
                                    @endforeach
                            </select>
                <div class="form-check">
                    <input class="form-check-input" name="addNew1" id="addNew1" type="checkbox" onchange="toggleActorNameFields()">
                    <label class="form-check-label" for="addNew1">Enter new actor</label>
                </div>
                <div class="form-row" hidden>
                    {{-- <label for="actorInputFirst1">Enter name</label><br> --}}
                    <div class="col">
                        <input class="form-control" id="actorInputFirst1" name="actorInputFirst1" placeholder="First name" required disabled>
                    </div>
                    <div class="col">
                        <input class="form-control" id="actorInputLast1" name="actorInputLast1" placeholder="Last name" required disabled>
                    </div>
                </div>
            </div>
        </div>

        <div id="genres">
            <h2>Genres</h2>
            <div class="form-group">
                <label for="genreSelect1">Select a genre</label>
                <select class="form-control" id="genreSelect1" name="genreSelect1" required>
                    @foreach ($genres as $genre)
                    <option label="{{$genre->Name}}" value="{{$genre->Genre_ID}}">
                    @endforeach
                </select>
            </div>
            <div class="form-group btn-group">
                <button class="btn btn-primary" type="button" onclick="addGenre()">+ Add genre</button>
                <button class="btn btn-danger" id="removeGButton" type="button" onclick="removeGenre()" disabled>- Remove genre</button>
            </div>
        </div>

        <h2>Files</h2>
        <div id="thumbnailFields">
            <div class="form-group">
                <label for="thumbnail">Upload thumbnail</label>
                <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg" onchange="loadImage()" required>
            </div>
            <img class="img-thumbnail" id="thumbnailPreview" width="200" hidden>
        </div>
        <div class="form-group">
            <label for="video">Upload video</label>
            <input type="file" class="form-control-file" name="video" id="video" accept=".mp4" onchange="initPlayer()" required>
        </div>
        <div class="form-group">
            <div id="videopreview" hidden>
                <video id="player" class="video-js vjs-default-skin vjs-big-play-centered" width="730" onloadedmetadata="loadDuration()"
                    controls>
            <div class="form-group">
                <label for="duration">Runtime:</label>
                <input type="text" class="form-control" id="duration" name="duration" readonly>
            </div>
        </div>
        </div>
        <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>

    <script src='https://vjs.zencdn.net/7.4.1/video.js'></script>

    <br> @if ($status === 1)
    <h2>Upload success</h2>
    @elseif ($status === 2)
    <h2>Upload failed</h2>
    @endif
@endsection

    <script>
        // this is called when the user changes what type of video
        // is being uploaded
        function changeMediaType() {
            var type = document.querySelector('input[name="mediatype"]:checked').value;
            var newShowEnabled = document.getElementById("addNewShow").checked;

            var i;
            var episodeElements = document.getElementById("showFields").querySelectorAll("input");
            for (i = 0; i < episodeElements.length; i++) {
                if (episodeElements[i].parentElement.id != "newShowFields" || newShowEnabled)
                    episodeElements[i].disabled = (type === "show") ? false : true;
            }

            document.getElementById("title").placeholder = (type === "show") ? "Episode Title" : "Movie Title";
            document.getElementById("showFields").hidden = (type === "show") ? false : true;
            toggleThumbnailFields();
        }

        // this is called when the user checks or unchecks
        // the "add new show" checkbox
        function toggleShowNameField()
        {
            var checked = document.getElementById("addNewShow").checked;
            document.getElementById("showSelect").disabled = checked;
            document.getElementById("newShowFields").hidden = !checked;

            var i;
            var newShowElements = document.getElementById("newShowFields").querySelectorAll("input");
            for (i = 0; i < newShowElements.length; i++)
            {
                newShowElements[i].disabled = !checked;
            }
            toggleThumbnailFields();
        }

        // this is called when the user checks or unchecks
        // an "add new actor" checkbox
        function toggleActorNameFields() {
            var actors = document.querySelectorAll('#actors > .form-group');
            var i;
            for (i = 0; i < actors.length; i++) {
                var checked = actors[i].querySelector('.form-check-input').checked;
                actors[i].querySelector('.form-row').hidden = !checked;
                actors[i].querySelector('#actorSelect' + (i + 1)).disabled = checked;
                nameInputs = actors[i].getElementsByClassName("form-row")[0].getElementsByTagName("input");
                nameInputs[0].disabled = !checked;
                nameInputs[1].disabled = !checked;
            }
        }

        // this is called when the user clicks the plus button
        // to add an actor
        function addActor() {
            var newCount = document.querySelectorAll('#actors > .form-group').length + 1;
            var firstActor = document.querySelector('#actors > .form-group');
            var newActor = firstActor.cloneNode(true);
            newActor.querySelector('label').htmlFor = "actorSelect" + newCount;
            newActor.querySelector('select').id = "actorSelect" + newCount;
            newActor.querySelector('select').name = "actorSelect" + newCount;
            newActor.querySelector('select').disabled = false;
            newActor.querySelector('.form-check-input').id = "addNew" + newCount;
            newActor.querySelector('.form-check-input').name = "addNew" + newCount;
            newActor.querySelector('.form-check-input').checked = false;
            newActor.querySelector('.form-check-label').htmlFor = "addNew" + newCount;

            //newActor.querySelector('.form-row > label').htmlFor = "actorInputFirst" + newCount;
            newActor.querySelector('.col > input').id = "actorInputFirst" + newCount;
            newActor.querySelector('.col > input').name = "actorInputFirst" + newCount;
            newActor.querySelector('.col > input').value = "";
            //newActor.querySelector('.form-row > label').htmlFor = "actorInputFirst" + newCount;
            newActor.querySelectorAll('.col > input')[1].id = "actorInputLast" + newCount;
            newActor.querySelectorAll('.col > input')[1].name = "actorInputLast" + newCount;
            newActor.querySelectorAll('.col > input')[1].value = "";

            newActor.querySelector('.form-row').hidden = true;
            document.getElementById("actors").appendChild(newActor);
            document.getElementById("removeButton").disabled = false;
        }

        // this is called when the user clicks the minus button
        // to remove an actor
        function removeActor() {
            var lastElement = document.getElementById("actors").lastElementChild;
            document.getElementById("actors").removeChild(lastElement);
            var count = document.querySelectorAll('#actors > .form-group').length;
            if (count == 1)
                document.getElementById("removeButton").disabled = true;
        }

        function addGenre()
        {
            var newCount = document.querySelectorAll('#genres > .form-group').length + 1; 
            var firstGenre = document.querySelector('#genres > .form-group'); 
            var newGenre = firstGenre.cloneNode(true);
            newGenre.querySelector('label').htmlFor = "genreSelect" + newCount;
            newGenre.querySelector('select').id = "genreSelect" + newCount;
            newGenre.querySelector('select').name = "genreSelect" + newCount;
            document.getElementById("genres").appendChild(newGenre);
            document.getElementById("removeGButton").disabled = false;
        }

        function removeGenre()
        {
            var lastElement = document.getElementById("genres").lastElementChild; 
            document.getElementById("genres").removeChild(lastElement);
            var count = document.querySelectorAll('#genres > .form-group').length; 
            if (count == 1) document.getElementById("removeGButton").disabled = true;
        }

        function addDirector()
        {
            var newCount = document.querySelectorAll('#actors > .form-group').length + 1; 
            var firstActor = document.querySelector('#actors > .form-group'); 
            var newActor = firstActor.cloneNode(true);
        }

        function removeDirector()
        {

        }

        // this is called when the value of the thumbnail input changes
        function loadImage() {
            var thumbnailPreview = document.getElementById("thumbnailPreview");
            var thumbnailInput = document.getElementById("thumbnail");
            var thumbnailFile = thumbnailInput.files[0];
            if (checkExtension(thumbnailFile.name, "jpg"))
            {
                var srcUrl = URL.createObjectURL(thumbnailFile);
                document.getElementById("thumbnailPreview").src = srcUrl;
                thumbnailPreview.hidden = false;
            }
            else
            {
                alert("Please choose a JPG file."); 
                thumbnailInput.value = null;
                thumbnailPreview.hidden = true;
            }
        }

        // this enables or disables the thumbnail fields
        // depending on the status of the form
        function toggleThumbnailFields()
        {
            var mediaType = document.querySelector('input[name="mediatype"]:checked').value; 
            var newShowEnabled = document.getElementById("addNewShow").checked;
            var thumbnailFields = document.getElementById("thumbnailFields");
            var thumbnailInput = document.getElementById("thumbnail");

            if (mediaType == "movie" || newShowEnabled)
            {
                thumbnailFields.hidden = false;
                thumbnailInput.disabled = false;
            }
            else
            {
                thumbnailFields.hidden = true;
                thumbnailInput.disabled = true;
            }
        }

        // this is called when the value of the video input changes
        function initPlayer() {
            var video = document.getElementById("video");
            if (video.files.length > 0) {
                document.getElementById("videopreview").hidden = false;
                var player = videojs('player');
                var videoFile = video.files[0];

                // when the player is ready, load the new
                // video information into the videojs object
                player.ready(function () {
                    var fileType = videoFile.type;
                    if (checkExtension(videoFile.name, "mp4"))
                    {
                        var url = URL.createObjectURL(videoFile);
                        this.src({ type: fileType, src: url });
                    }
                    else
                    {
                        alert("Please choose a MP4 file.");
                        video.value = null;
                        document.getElementById("videopreview").hidden = true;
                    }
                });
            }
            else
            {
                document.getElementById("videopreview").hidden = true;
            }
        }

        // checks if a given filename has the specified extension
        function checkExtension(filename, extension) 
        { 
            return (filename.substring(filename.lastIndexOf('.') + 1, filename.length) == extension);
        }

        // this is called once the videojs player has finished
        // loading metadata for the video
        function loadDuration() {
            var player = videojs('player');
            var fullDuration = videojs.formatTime(player.duration());
            document.getElementById("duration").setAttribute("value", fullDuration);
            //alert(fullDuration);
        }

    </script>

</body>

</html>