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
                //var type = document.getElement("mediatype").value;
                var type = document.querySelector('input[name="mediatype"]:checked').value;
                //window.alert("type: " + type);
                //window.alert();
                //var show = type === "show";
                //var episode = type === "episode";

                //document.getElementById("episodeFields").style.display = (type === "episode") ? "block" : "none";
                //document.getElementById("nonShowFields").style.display = (type === "show") ? "none" : "block";
                //document.getElementById("nonEpisodeFields").style.display = (type === "episode") ? "none" : "block";

                var i;
                var episodeElements = document.getElementById("episodeFields").querySelectorAll("input");
                for (i = 0; i < episodeElements.length; i++)
                {
                    episodeElements[i].disabled = (type === "episode") ? false : true;
                }

                var nonShowElements = document.getElementById("nonShowFields").querySelectorAll("input");
                for (i = 0; i < nonShowElements.length; i++)
                {
                    nonShowElements[i].disabled = (type === "show") ? true : false;
                }

                var nonEpisodeFields = document.getElementById("nonEpisodeFields").querySelectorAll("input");
                for (i = 0; i < nonEpisodeFields.length; i++)
                {
                    nonEpisodeFields[i].disabled = (type === "episode") ? true : false;
                }

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

                document.getElementById("episodeFields").hidden = (type === "episode") ? false : true;
                document.getElementById("nonShowFields").hidden = (type === "show") ? true : false;
                document.getElementById("nonEpisodeFields").hidden = (type === "episode") ? true : false;
            }
            //function toggle_
        </script>

        <h1>Upload video</h1>

        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="radio" name="mediatype" id="moviechoice" onclick="updateForm()" checked="true" value="movie" required> Movie
            <input type="radio" name="mediatype" id="episodechoice" onclick="updateForm()" value="episode" > Episode 
            <input type="radio" name="mediatype" id="showchoice" onclick="updateForm()" value="show" > Show <br>
            <input type="text" name="title" id="title" placeholder="Title" required> <br>
            <input type="text" name="year" id="year" minlength="4" maxlength="4" placeholder="Year" required> <br>
            <div id="episodeFields" hidden>
                <input type="number" name="showId" id="showId" placeholder="Show ID" required disabled="true" > <br>
                <input type="number" name="seasonNumber" id="seasonNumber" placeholder="Season number" required disabled="true"> <br>
                <input type="number" name="episodeNumber" id="episodeNumber" placeholder="Episode number" required disabled="true"> <br>
            </div>
            <input type="text" name="summary" id="summary" placeholder="Summary" required> <br/>
            <div id="nonEpisodeFields">
                <input type="text" name="subscription" id="subscription" placeholder="Subscription" required> <br>
            </div>
            <div id="nonShowFields">
                <br> <label>Runtime: 
                    <input type="number" name="hours" id="hours" min="0" max="10" placeholder="h" required> :
                    <input type="number" name="minutes" id="minutes" min="0" max="59" placeholder="m" required> :
                    <input type="number" name="seconds" id="seconds" min="0" max="59" placeholder="s" required>
                </label> <br> <br>
                <input type="file" name="video" id="video" required>
            </div>
            <br> <input type="submit" name="submit" >
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
