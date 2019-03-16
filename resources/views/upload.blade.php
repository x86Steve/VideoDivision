<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Upload - VideoDivision</title>
    </head>
    <body>
        @extends('layouts.app')

        @section('content')
        
        <h1>Upload video</h1>

        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="radio" name="videotype" id="videotype" value="movie" onclick="EnableVideo(form)"> Movie
            <input type="radio" name="videotype" id="videotype" value="episode" > Episode <br/>
            <input type="text" name="title" id="title" placeholder="Title"> <br/>
            <input type="number" name="year" id="year" minlength="4" maxlength="4" placeholder="Year" > <br/>
            <input type="text" name="description" id="description" placeholder="Description" > <br/>
            <input type="text" name="subscription" id="subscription" placeholder="Subscription" > <br/>
            <input type="file" name="video" id="video" >
            <input type="submit" name="submit" >
        </form>
        
        
        @endsection

            <?php
//                function EnableVideo($form form)
//                {
//                    $doc = new DOMDocument;
//                    
//                }
            ?>
    </body>
</html>
