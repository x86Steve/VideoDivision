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

        if (\Auth::check()) {
            $userID = \Auth::user()->id;
        } else {
            $userID = -1;
        }

        $isSubbed = $this->isSubbed($video_id, $userID);

        if ($isMovie) {
            $extra = $this->getMovieByID($video_id);
            $cast = $this->getCastOfMovie($video_id);
            $directors = $this->getDirectorsOfMovie($video_id);
        } else {
            $extra = $this->getFirstEpisodeByVideoID($video_id);
            $cast = $this->getCastOfShow($video_id);
            $directors = $this->getDirectorsOfShow($video_id);
        }

        return view('watch', [
            'User_ID' => $userID,
            'isSubbed' => $isSubbed,
            'isMovie' => $isMovie,
            'file' => $results,
            'extra' => $extra,
            'directors' => json_decode(json_encode($directors), true),
            'cast' => json_decode(json_encode($cast), true),
            'genres' => json_decode(json_encode($genres), true)]);
    }

    function getEpisodeView($show_id, $season_number, $episode_number)
    {

        $results = $this->getVideoByID($show_id);
        $isMovie = $results[0]->IsMovie;
        $lastEpisode = $this->getLastEpisodeByVideoID($show_id);

        //Special case for last season and next button; 0 means last episode of last season
        $lastSeasonFlag = 1;

        $lastEpisodeOfSeriesNumber = $lastEpisode->Episode_Number;
        $lastSeasonNumber = $lastEpisode->Season_Number;
        $numberOfSeasons = $lastSeasonNumber;


        $episodeInfo = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$show_id")
            ->where('Episode.Episode_Number', '=', "$episode_number")
            ->select('Episode.*')
            ->first();

        $episode_number = $episodeInfo->Episode_Number;
        $episode_title = $episodeInfo->Episode_Title;
        $file_path = $episodeInfo->File_Path;
        $season_number = $episodeInfo->Season_Number;
        $episode_id = $episodeInfo->Episode_ID;

        if (($lastSeasonNumber == $season_number) && ($episode_number == $lastEpisodeOfSeriesNumber))
        {
            $lastSeasonFlag = 0;
        }

        if (($episode_number == $lastEpisodeOfSeriesNumber) && ($season_number != $lastSeasonNumber)) {
            $newSeason = $season_number + 1;
            $resetEpisodeNumber = 1;

            return view('watch', [
                'extra' => $episodeInfo,
                'episode_number' => $resetEpisodeNumber,
                'episode_title' => $episode_title,
                'file' => $results,
                'isMovie' => $isMovie,
                'file_path' => $file_path,
                'season_number' => $newSeason,
                'episode_id' => $episode_id,
                'last_episode_of_series_number' => $lastEpisodeOfSeriesNumber,
                'last_season_number' => $lastSeasonNumber,
                'number_of_seasons' => $numberOfSeasons,
                'last_season_flag' => $lastSeasonFlag
            ]);
        }


        return view('watch', [
            'extra' => $episodeInfo,
            'episode_number' => $episode_number,
            'episode_title' => $episode_title,
            'file' => $results,
            'isMovie' => $isMovie,
            'file_path' => $file_path,
            'season_number' => $season_number,
            'episode_id' => $episode_id,
            'last_episode_of_series_number' => $lastEpisodeOfSeriesNumber,
            'number_of_seasons' => $numberOfSeasons,
            'last_season_number' => $lastSeasonNumber,
            'last_season_flag' => $lastSeasonFlag
        ]);
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

    function getFirstView($video_id)
    {

        $results = $this->getVideoByID($video_id);
        $isMovie = $results[0]->IsMovie;
        $lastEpisode = $this->getLastEpisodeByVideoID($video_id);
        $lastEpisodeOfSeriesNumber = $lastEpisode->Episode_Number;
        $lastSeasonNumber = $lastEpisode->Season_Number;
        $numberOfSeasons = $lastSeasonNumber;
        $lastSeasonFlag = 1;

        $firstEpisode = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$video_id")
            ->select('Episode.*')
            ->orderBy('Season_Number', 'asc')
            ->orderBy('Episode_Number', 'asc')
            ->first();

        $episode_number = $firstEpisode->Episode_Number;
        $episode_title = $firstEpisode->Episode_Title;
        $season_number = $firstEpisode->Season_Number;
        $file_path = $firstEpisode->File_Path;
        $episode_id = $firstEpisode->Episode_ID;

        return view('watch', [
            'extra' => $firstEpisode,
            'episode_number' => $episode_number,
            'episode_title' => $episode_title,
            'file' => $results,
            'isMovie' => $isMovie,
            'file_path' => $file_path,
            'season_number' => $season_number,
            'episode_id' => $episode_id,
            'last_episode_of_series_number' => $lastEpisodeOfSeriesNumber,
            'last_season_number' => $lastSeasonNumber,
            'number_of_seasons' => $numberOfSeasons,
            'last_season_number' => $lastSeasonNumber,
            'last_season_flag' => $lastSeasonFlag
        ]);
    }

    function getEpisodeInfo($episode_id)
    {
        $episodeInfo = DB::table('Episode')->where('Episode.Episode_ID', '=', "$episode_id")->select('Episode.*')->get();
        return $episodeInfo;
    }
}
