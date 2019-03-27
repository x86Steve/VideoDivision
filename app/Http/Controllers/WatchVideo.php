<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class WatchVideo extends Search\SearchController
{

    function getView($video_id)
    {

        $results = $this->getVideoByID($video_id);

        $genres = $this->getGenres($video_id);

        $isMovie = $results[0]->IsMovie;

        if ($isMovie) {
            $MovieInfo = DB::table('Movie')->where('Movie.Movie_ID', '=', "$video_id")->select('Movie.*')->get();
            $file_path = $MovieInfo[0]->File_Path;

            $extra = $this->getMovieByID($video_id);
            $cast = $this->getCastOfMovie($video_id);
            $directors = $this->getDirectorsOfMovie($video_id);
        } else {
            $extra = $this->getLastEpisodeByVideoID($video_id);
            $cast = $this->getCastOfShow($video_id);
            $directors = $this->getDirectorsOfShow($video_id);

            $episode_id = $extra->Episode_Number;
            $episodeInfo = DB::table('Episode')->where('Episode.Episode_ID', '=', "$episode_id")->select('Episode.*')->get();
            $file_path = $episodeInfo[0]->File_Path;
        }

        return view('watch', ['isMovie' => $isMovie,
            'file' => $results,
            'extra' => $extra,
            'file_path' => $file_path,
            'directors' => json_decode(json_encode($directors), true),
            'cast' => json_decode(json_encode($cast), true),
            'genres' => json_decode(json_encode($genres), true)]);

    }

    function getEpisodeView($video_id, $episode_id)
    {
        $results = $this->getVideoByID($video_id);

        $genres = $this->getGenres($video_id);

        $isMovie = $results[0]->IsMovie;
        $episode_title = null;

        if ($isMovie) {
            $MovieInfo = DB::table('Movie')->where('Movie.Movie_ID', '=', "$video_id")->select('Movie.*')->get();
            $file_path = $MovieInfo[0]->File_Path;

            $extra = $this->getMovieByID($video_id);
            $cast = $this->getCastOfMovie($video_id);
            $directors = $this->getDirectorsOfMovie($video_id);
        } else {
            $extra = $this->getFirstEpisodeByVideoID($video_id);
            $cast = $this->getCastOfShow($video_id);
            $directors = $this->getDirectorsOfShow($video_id);

            $episodeInfo = $this->getEpisodeInfo($episode_id);
            $episode_title = $episodeInfo[0]->Episode_Title;
            $file_path = $episodeInfo[0]->File_Path;
        }

        return view('watch', ['isMovie' => $isMovie,
            'file' => $results,
            'extra' => $extra,
            'file_path' => $file_path,
            'episode_title' => $episode_title,
            'episode_number' => $episode_id,
            'directors' => json_decode(json_encode($directors), true),
            'cast' => json_decode(json_encode($cast), true),
            'genres' => json_decode(json_encode($genres), true)]);


    }

    function getFirstEpisodeByVideoID($video_id)
    {
        $firstEpisode = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$video_id")
            ->select('Episode.*')
            ->orderBy('Season_Number', 'asc')
            ->orderBy('Episode_Number', 'asc')
            ->first();
        return $firstEpisode;
    }

    function getEpisodeInfo($episode_id)
    {
        $episodeInfo = DB::table('Episode')->where('Episode.Episode_ID', '=', "$episode_id")->select('Episode.*')->get();
        return $episodeInfo;
    }
}
