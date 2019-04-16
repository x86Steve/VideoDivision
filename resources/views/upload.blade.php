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

<br> @if ($status === 1)
<h2>Upload success</h2>
@elseif ($status === 2)
<h2>Upload failed</h2>
@endif

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
                <div class="form-group">
                    <label for="showInput">Enter show name</label>
                    <input class="form-control" id="showInput" name="showInput" placeholder="Show Name" required disabled>                    {{-- <input class="form-control" id="actorInputLast1" name="actorInputLast1"> --}}
                </div>
                <div class="form-group">
                    <label for="showSummary">Enter show summary</label>
                    <input class="form-control" id="showSummary" name="showSummary" placeholder="Summary text..." required disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="seasonNumber">Season Number</label>
                <input type="number" class="form-control" name="seasonNumber" id="seasonNumber" placeholder="#" required disabled>
            </div>
            <div class="form-group">
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
                <label for="directorSelect1">Select an existing director</label>
                <select class="form-control" id="directorSelect1" name="directorSelect1" required>
                                @foreach ($directors as $director)
                                <option label="{{$director->First_Name}} {{$director->Last_Name}}" value="{{$director->Director_ID}}">
                                    @endforeach
                            </select>
                <div class="form-check">
                    <input class="form-check-input" name="addNewD1" id="addNewD1" type="checkbox" onchange="toggleDirectorNameFields()">
                    <label class="form-check-label" for="addNewD1">Enter new director</label>
                </div>
                <div class="form-row" hidden>
                    <div class="col">
                        <input class="form-control" id="directorInputFirst1" name="directorInputFirst1" placeholder="First name" required disabled>
                    </div>
                    <div class="col">
                        <input class="form-control" id="directorInputLast1" name="directorInputLast1" placeholder="Last name" required disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group btn-group">
            <button class="btn btn-primary" type="button" onclick="addDirector()">+ Add director</button>
            <button class="btn btn-danger" id="removeDButton" type="button" onclick="removeDirector()" disabled>- Remove director</button>
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
            <div>
                <div class="form-group btn-group">
                    <button class="btn btn-primary" type="button" onclick="addGenre()">+ Add genre</button>
                    <button class="btn btn-danger" id="removeGButton" type="button" onclick="removeGenre()" disabled>- Remove genre</button>
                </div>
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
            </div>
            <input id="duration" name="duration" readonly hidden>
        </div>
        <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>

    <script src='https://vjs.zencdn.net/7.4.1/video.js'></script>

    
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
            toggleThumbnailAndGenreFields();
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
            toggleThumbnailAndGenreFields();
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

            newActor.querySelector('.col > input').id = "actorInputFirst" + newCount;
            newActor.querySelector('.col > input').name = "actorInputFirst" + newCount;
            newActor.querySelector('.col > input').value = "";
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

        // this is called when the user clicks the button 
        // to add a genre
        function addGenre()
        {
            var newCount = document.querySelectorAll('#genres > .form-group').length + 1; 
            var firstGenre = document.querySelector('#genres > .form-group');
            var lastNode = document.getElementById("genres").lastChild;
            var newGenre = firstGenre.cloneNode(true);
            newGenre.querySelector('label').htmlFor = "genreSelect" + newCount;
            newGenre.querySelector('select').id = "genreSelect" + newCount;
            newGenre.querySelector('select').name = "genreSelect" + newCount;
            document.getElementById("genres").insertBefore(newGenre, lastNode); //appendChild(newGenre);
            document.getElementById("removeGButton").disabled = false;
        }

        // this is called when the user clicks the button 
        // to remove a genre
        function removeGenre()
        {
            var lastGenre = document.getElementById("genres").lastChild.previousSibling; 
            document.getElementById("genres").removeChild(lastGenre);
            var count = document.querySelectorAll('#genres > .form-group').length; 
            if (count == 1) document.getElementById("removeGButton").disabled = true;
        }

        // this is called when the user clicks the button 
        // to add a director
        function addDirector()
        {
            var newCount = document.querySelectorAll('#directors > .form-group').length + 1; 
            var firstDirector = document.querySelector('#directors > .form-group'); 
            var newDirector = firstDirector.cloneNode(true);

            newDirector.querySelector('label').htmlFor = "directorSelect" + newCount;
            newDirector.querySelector('select').id = "directorSelect" + newCount;
            newDirector.querySelector('select').name = "directorSelect" + newCount;
            newDirector.querySelector('select').disabled = false;
            newDirector.querySelector('.form-check-input').id = "addNewD" + newCount;
            newDirector.querySelector('.form-check-input').name = "addNewD" + newCount;
            newDirector.querySelector('.form-check-input').checked = false;
            newDirector.querySelector('.form-check-label').htmlFor = "addNewD" + newCount;

            newDirector.querySelector('.col > input').id = "directorInputFirst" + newCount;
            newDirector.querySelector('.col > input').name = "directorInputFirst" + newCount;
            newDirector.querySelector('.col > input').value = "";
            newDirector.querySelectorAll('.col > input')[1].id = "directorInputLast" + newCount;
            newDirector.querySelectorAll('.col > input')[1].name = "directorInputLast" + newCount;
            newDirector.querySelectorAll('.col > input')[1].value = "";

            newDirector.querySelector('.form-row').hidden = true;
            document.getElementById("directors").appendChild(newDirector);
            document.getElementById("removeDButton").disabled = false;
        }

        // this is called when the user clicks the button 
        // to remove a director
        function removeDirector()
        {
            var lastElement = document.getElementById("directors").lastElementChild;
            document.getElementById("directors").removeChild(lastElement);
            var count = document.querySelectorAll('#directors > .form-group').length;
            if (count == 1)
                document.getElementById("removeDButton").disabled = true;
        }

        // this is called when the user checks or unchecks
        // an "add new actor" checkbox
        function toggleDirectorNameFields()
        {
            var directors = document.querySelectorAll('#directors > .form-group');
            var i;
            for (i = 0; i < directors.length; i++) {
                var checked = directors[i].querySelector('.form-check-input').checked;
                directors[i].querySelector('.form-row').hidden = !checked;
                directors[i].querySelector('#directorSelect' + (i + 1)).disabled = checked;
                nameInputs = directors[i].getElementsByClassName("form-row")[0].getElementsByTagName("input");
                nameInputs[0].disabled = !checked;
                nameInputs[1].disabled = !checked;
            }
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
        function toggleThumbnailAndGenreFields()
        {
            var mediaType = document.querySelector('input[name="mediatype"]:checked').value; 
            var newShowEnabled = document.getElementById("addNewShow").checked;
            var thumbnailFields = document.getElementById("thumbnailFields");
            var thumbnailInput = document.getElementById("thumbnail");
            var genreFields = document.getElementById("genres");

            if (mediaType == "movie" || newShowEnabled)
            {
                thumbnailFields.hidden = false;
                thumbnailInput.disabled = false;
                genreFields.hidden = false;
            }
            else
            {
                thumbnailFields.hidden = true;
                thumbnailInput.disabled = true;
                genreFields.hidden = true;
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
            var duration = SToHHMMSS(player.duration());
            document.getElementById("duration").setAttribute("value", duration);
        }

        function SToHHMMSS(s)
        {
            var hours = parseInt(s / 3600);
            s %= 3600;
            var minutes = parseInt(s / 60);
            s = parseInt(s % 60);
            return hours + ":" + minutes + ":" + s;
            //return 4;
        }

    </script>

</body>

</html>