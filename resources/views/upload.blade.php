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

        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="title" id="title" />
            <input type="file" name="video" id="video" />
            <input type="submit" name="submit" value="Upload" />
        </form>
        
        
        @endsection

            <?php
            // put your code here
            ?>
    </body>
</html>
