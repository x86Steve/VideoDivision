<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class SearchController extends Controller
{

    private function getMoviesByActor($q)
    {
        $moviesByActor = DB::table('Video')
            ->join('Cast', 'Cast.Movie_ID', 'Video.Video_ID')
            ->join('Actor', 'Actor.Actor_ID', 'Cast.Actor_ID')
            ->where('Actor.first_name', 'like' , "%$q%")
            ->OrWhereRaw("concat(Actor.First_name, ' ', Actor.Last_name) like '%$q%' ")
            ->select('Video.*');
        //->get()
        //->unique('Video_ID');

        return $moviesByActor;
    }
    private function getShowsByActor($q)
    {
        $showsByActor = DB::table('Video')
            ->join('Episode', 'Episode.Show_ID', 'Video.Video_ID')
            ->join('Cast', 'Cast.Episode_ID', 'Episode.Episode_ID')
            ->join('Actor', 'Actor.Actor_ID', 'Cast.Actor_ID')
            ->where('Actor.first_name', 'like' , "%$q%")
            ->orWhereRaw("concat(Actor.First_name, ' ', Actor.Last_name) like '%$q%' ")
            ->select('Video.*');
        //->get()
        //->unique('Video_ID');

        return $showsByActor;
    }

    private function getVideosByActors($q)
    {
        $ByActor = $this->getMoviesByActor($q)
            ->unionAll($this->getShowsByActor($q))
            ->orderBy('Title','asc')
            ->get()->unique('Video_ID');

        return $ByActor;
    }

    private function getMoviesByDirector($q)
    {
        $moviesByDirector = DB::table('Video')
            ->join('Directors', 'Directors.Movie_ID', 'Video.Video_ID')
            ->join('Director', 'Director.Director_ID', 'Directors.Director_ID')
            ->where('Director.first_name', 'like' , "%$q%")
            ->OrWhereRaw("concat(Director.First_name, ' ', Director.Last_name) like '%$q%' ")
            ->select('Video.*');
        //->get()
        //->unique('Video_ID');

        return $moviesByDirector;
    }
    private function getShowsByDirector($q)
    {
        $showsByActor = DB::table('Video')
            ->join('Episode', 'Episode.Show_ID', 'Video.Video_ID')
            ->join('Directors', 'Directors.Episode_ID', 'Episode.Episode_ID')
            ->join('Director', 'Director.Director_ID', 'Directors.Director_ID')
            ->where('Director.first_name', 'like' , "%$q%")
            ->OrWhereRaw("concat(Director.First_name, ' ', Director.Last_name) like '%$q%' ")
            ->select('Video.*');
        //->get()
        //->unique('Video_ID');

        return $showsByActor;
    }

    private function getVideosByDirector($q)
    {
        $ByDirector = $this->getMoviesByDirector($q)
            ->unionAll($this->getShowsByDirector($q))
            ->orderBy('Title','asc')
            ->get()->unique('Video_ID');

        return $ByDirector;
    }

    private function getVideosByTitle($q)
    {
        $videosByTitle = DB::table('Video')
            ->where('Video.Title', 'like' , "%$q%")
            ->select('Video.*')
            ->get()
            ->unique('Video_ID');

        return $videosByTitle;
    }

    private function getVideosByGenre($q)
    {
        $moviesByGenre = DB::table('Video')
                    ->join ('Genres', 'Genres.Video_ID', 'Video.Video_ID')
                    ->join ('Genre', 'Genre.Genre_ID', 'Genres.Genre_ID')
                    ->where('Genre.Name', 'like', "%$q%")
                    ->select('Video.*')
                    ->get()
                    ->unique('Video_ID');

        return $moviesByGenre;
    }

    private function getVideosBySubscription($q)
    {
        $videosByGenre = DB::table('Video')
            ->where('Video.subscription', 'like' , "%$q%")
            ->select('Video.*')
            ->get()
            ->unique('Video_ID');

        return $videosByGenre;
    }
    private function formatSearch($table, $tableName, $results, $q)
    {
        if($table -> isEmpty())
        {
            $results .= "<h4><br><Strong>No " .$tableName . "s match your search \"$q\".</Strong></h4><br>";
        }
        else{
            $num = $table -> count();

            $results .= "<br><h4><strong>$num entries match \"$q\" by $tableName:</strong></h4>";

            foreach ($table as $title)
            {
                $results .= "<h4> $title->Title </h4>";
            }

        }

        return $results;
    }

    public function basicSearch()
    {

        $q = Input::get ( 'q' );
        if ($q=="")
        {
            return view('search', ['results' => "<h3>Please input a search in the bar at the top right.</h3>", 'q' =>$q]);
        }
        return view('search', ['results' => $this->searchAll($q), 'q' =>$q]);




    }

    private function searchAll($q)
    {
        $ByTitle = $this->getVideosByTitle($q);
        $ByActor = $this->getVideosByActors($q);
        $ByDirector = $this->getVideosByDirector($q);
        $ByGenre = $this->getVideosByGenre($q);
        $BySub = $this->getVideosBySubscription($q);


        $results ="<h2>Movies and TV shows matching search: </h2>";

        $results = $this->formatSearch($ByTitle, "title", $results, $q);
        $results = $this->formatSearch($ByActor, "actor", $results, $q);
        $results = $this->formatSearch($ByDirector, "director", $results, $q);
        $results = $this->formatSearch($ByGenre, "genre", $results, $q);
        $results = $this->formatSearch($BySub, "subscription", $results, $q);

        return $results;
    }
}
