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
            <input type="radio" name="videotype" id="videotype" value="1" required> Movie
            <input type="radio" name="videotype" id="videotype" value="0" > Episode <br>
            <input type="text" name="title" id="title" placeholder="Title" required> <br>
            <input type="text" name="year" id="year" minlength="4" maxlength="4" placeholder="Year" required> <br>
            <input type="text" name="summary" id="summary" placeholder="Summary" required> <br/>
            <input type="text" name="subscription" id="subscription" placeholder="Subscription" required> <br>
            <label>Runtime: 
                <input type="number" name="hours" id="hours" min="0" max="10" required> :
                <input type="number" name="minutes" id="minutes" min="0" max="59" required> :
                <input type="number" name="seconds" id="seconds" min="0" max="59" required>
            </label> <br>
            <input type="file" name="video" id="video" required>
            <input type="submit" name="submit" >
        </form>
        
        <br>
        
        @if ($status === 1)
        <h2>Upload success</h2>
        @elseif ($status === 2)
        <h2>Upload failed</h2>
        @endif
        
        
        @endsection

            <?php
                
            ?>
    </body>
</html>
