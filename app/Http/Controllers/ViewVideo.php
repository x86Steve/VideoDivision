<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class ViewVideo extends Search\SearchController
{
    function viewDetails($Video_ID)
    {
        $details = $Video_ID;
        return view('view_video_details', ['details' =>$details]);
    }

    function getView()
    {
        $video_id = Input::get ( 'video' );

        $results = $this -> getVideoByID($video_id);

        $genres = $this -> getGenres($video_id);

        $isMovie = $results[0]->IsMovie;

        if ($isMovie){
            $extra = $this -> getMovieByID($video_id);
            $cast = $this -> getCastOfMovie($video_id);
            $directors = $this -> getDirectorsOfMovie($video_id);
        }
        else{
            $extra = $this -> getLastEpisodeByVideoID($video_id);
            $cast = $this -> getCastOfShow($video_id);
            $directors = $this -> getDirectorsOfShow($video_id);
        }

        return view('view_video_details',  ['isMovie' => $isMovie,
            'file' => $results,
            'extra'=>$extra,
            'directors' => json_decode(json_encode($directors),true),
            'cast' => json_decode(json_encode($cast),true),
            'genres'=>json_decode(json_encode($genres),true)]);

    }

}
