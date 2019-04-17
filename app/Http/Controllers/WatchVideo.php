<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Auth;
class WatchVideo extends Search\SearchController
{

    function getView($video_id)
    {

        $results = $this->getVideoByID($video_id);
        $isMovie = $results[0]->IsMovie;

        if (Auth::guest())
            return redirect()->route('login');

        if ($isMovie) {
            $extra = $this->getMovieByID($video_id);
        } else {
            $extra = $this->getFirstEpisodeByVideoID($video_id);
        }
        $file_path = $extra->File_Path;

        return view('watch', [
            'isMovie' => $isMovie,
            'file' => $results,
            'extra' => $extra,
            'file_path' => $file_path
        ]);
    }

    //v2
    function getEpisodeView($show_id, $season_number, $episode_number)
    {

        if (Auth::guest())
            return redirect()->route('login');

        $file = $this->getVideoByID($show_id);
        $isMovie = $file[0]->IsMovie;
        $lastEpisode = $this->getLastEpisodeByVideoID($show_id);
        $isSpecialEpisode = 0; //0 ignore, 1 last ep of season, 2 last episode of series,  3 for 1st episode of non-first season, 4 if first episode of series
        $lastEpOfSeason = $this->getLastEpOfSeason($show_id, $season_number);
        $episodeInfo = $this->getEpisodeInfo($show_id, $episode_number, $season_number);

        $lastEpOfSeasonNumber = $lastEpOfSeason->Episode_Number;
        $lastEpOfSeriesNumber = $lastEpisode->Episode_Number;
        $numberOfSeasons = $lastEpisode->Season_Number;
        $previousEp = 0;// used for special case 3

        if (($episode_number == $lastEpOfSeasonNumber) && ($season_number !=$numberOfSeasons))
        {
            $isSpecialEpisode = 1; //last episode of season
        }
        elseif (($episode_number == $lastEpOfSeriesNumber) && ($season_number == $numberOfSeasons))
        {
            $isSpecialEpisode = 2; //last episode of series
        }
        elseif (($episode_number == 1) && ($season_number != 1))
        {
            $isSpecialEpisode = 3; //1st episode of a non-first season
            $previousEp = $this->getLastEpOfSeason($show_id, $season_number - 1)->Episode_Number;
        }
        elseif (($episode_number == 1) && ($season_number == 1))
        {
            $isSpecialEpisode = 4; //first episode of series
        }

        $file_path = $episodeInfo->File_Path;
        $episode_title = $episodeInfo->Episode_Title;

        return view('watch', [
            'file' => $file,
            'file_path' => $file_path,
            'is_special_episode' => $isSpecialEpisode,
            'season_number' => $season_number,
            'episode_number' => $episode_number,
            'episode_title' => $episode_title,
            'isMovie' => $isMovie,
            'number_of_seasons' => $numberOfSeasons,
            'previous_episode' => $previousEp
        ]);
    }

    function getFirstView($show_id)
    {
        if (Auth::guest())
            return redirect()->route('login');

        $file = $this->getVideoByID($show_id);
        $isMovie = $file[0]->IsMovie;
        $episodeInfo = $this->getEpisodeInfo($show_id, 1, 1);
        $file_path = $episodeInfo->File_Path;
        $episode_title = $episodeInfo->Episode_Title;
        $seriesInfoDesc = $this->getSeriesInfoDesc($show_id);
        $numberOfSeasons = $seriesInfoDesc->Season_Number;
        $previousEp = 1;

        return view('watch', [
            'file' => $file,
            'file_path' => $file_path,
            'is_special_episode' => 4,
            'season_number' => 1,
            'episode_number' => 1,
            'episode_title' => $episode_title,
            'isMovie' => $isMovie,
            'number_of_seasons' => $numberOfSeasons,
            'previous_episode' => $previousEp
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

    function getLastEpOfSeason($show_id, $season_number)
    {
        return $seasonDescending = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$show_id")
            ->where('Episode.Season_Number', '=', "$season_number")
            ->select('Episode.*')
            ->orderBy('Episode_Number', 'desc')
            ->first();
    }

    function getSeriesInfoDesc($show_id)
    {
        return $seriesInfo = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$show_id")
            ->select('Episode.*')
            ->orderBy('Season_Number', 'desc')
            ->orderBy('Episode_Number', 'desc')
            ->first();
    }

    function getSeasonInfo($show_id, $season_number)
    {
        return DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$show_id")
            ->where('Episode.Season_Number', '=', "$season_number")
            ->orderBy('Episode_Number', 'desc')
            ->get();
    }

    function getEpisodeInfo($show_id, $episode_number, $season_number)
    {
        return $episodeInfo = DB::table('Episode')
            ->where('Episode.Show_ID', '=', "$show_id")
            ->where('Episode.Season_Number', '=', "$season_number")
            ->where('Episode.Episode_Number', '=', "$episode_number")
            ->select('Episode.*')
            ->first();
    }
}
