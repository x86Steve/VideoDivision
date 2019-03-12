<?php

echo('

<div class="jumbotron text-center">
    <div class="container" >
        <h1> Welcome to VideoDivision.net</h1>
        <p class="lead">
            Say goodbye to Netflix , Hulu, and Crunchyroll!
        </p>
    </div>
</div>
');


$movies = DB::table('Video')->orderBy('Title','asc')->get();

echo("Our Current Titles: <p>");

foreach ($movies as $title)
{
	echo ("<p>" . $title->Title );
}
