<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

class ViewVideo extends Search\SearchController
{
    //I cant remember if this function is ever called but here it is.
    /**function viewDetails($Video_ID)
    {
        $details = $Video_ID;
        return view('view_video_details', ['details' =>$details]);
    }
     * **/

    //Used to see video details page
    function getView()
    {
        $video_id = Input::get ( 'video' );

        $results = $this -> getVideoByID($video_id);

        $genres = $this -> getGenres($video_id);

        $isMovie = $results[0]->IsMovie;

        if (\Auth::check()) {
            $userID = \Auth::user()->id;
        }
        else
        {
            $userID = -1;
        }

        $isSubbed = $this -> isSubbed($video_id, $userID);
        $isFav = $this -> isFaved($video_id, $userID);

        if ($isMovie){
            $extra = $this -> getMovieByID($video_id);
            $cast = $this -> getCastOfMovie($video_id);
            $directors = $this -> getDirectorsOfMovie($video_id);
            $num_of_episodes = -1;
        }
        else{
            $extra = $this -> getLastEpisodeByVideoID($video_id);
            $cast = $this -> getCastOfShow($video_id);
            $directors = $this -> getDirectorsOfShow($video_id);
            $num_of_episodes = $this -> getEpisodeCount($video_id);
        }

        return view('view_video_details',  [
            'User_ID' => $userID,
            'isSubbed' => $isSubbed,
            'isMovie' => $isMovie,
            'isFav' => $isFav,
            'file' => $results,
            'extra'=>$extra,
            'num_of_episodes' => $num_of_episodes,
            'directors' => json_decode(json_encode($directors),true),
            'cast' => json_decode(json_encode($cast),true),
            'genres'=>json_decode(json_encode($genres),true)]);

    }

    //Used to add entries to the database showing the user has subscribed
    function postHandler()
    {

        $postType = Request::get('postType');

        if($postType == 0)
        {

            $User_ID = Request::get('User_ID');

            #CHANGE TO BE DYNAMIC NUMBER INSTEAD OF 10
            if($this->getSubs($User_ID)->count() < 10)
            {
                $Video_ID = Request::get('Video_ID');
                $isMovie = Request::get('isMovie');

                DB::table('active_subscriptions')->insertGetId(
                    ['User_ID' => $User_ID, 'Video_ID' => $Video_ID, 'isMovie' =>$isMovie]);

                return $this->getView();
            }
            else
            {
                return $this->getView();
            }
        }
        else if($postType == 1)
        {
            $User_ID = Request::get('User_ID');

            $Video_ID = Request::get('Video_ID');

            if($this->isFaved($Video_ID, $User_ID) === false)
            DB::table('favorites')->insertGetId(
                ['User_ID' => $User_ID, 'Video_ID' => $Video_ID]);

            return $this->getView();

        }
        else if($postType == 2)
        {
            $User_ID = Request::get('User_ID');

            $Video_ID = Request::get('Video_ID');

            if($this->isFaved($Video_ID, $User_ID) === true)
                DB::table('favorites')
                    ->where('User_ID', '=', "$User_ID")
                    ->where('Video_ID', '=',"$Video_ID")
                    ->delete()
                ;

            return $this->getView();
        }

    }

    //Used to add entries to the database showing the user has subscribed
    function favorite()
    {

    }

    //Used to see the videos you are currently subbed to
    function getMyVideosView()
    {
        $output = '';

        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        }
        else
        {
            $user_id = -1;
        }


        $user_videos = DB::table('Video')
            ->join('active_subscriptions', 'active_subscriptions.Video_ID', 'Video.Video_ID')
            ->where('active_subscriptions.User_ID', '=', "$user_id")
            ->select('Video.*')
            ->orderBy('Title','asc')
            ->get()
            ->unique('Video_ID')
            ;


        $total_row = $user_videos -> count();

        if($total_row > 0 )
        {
            $counter = 0;
            $numOfCols = 3;
            $bootstrapColWidth = floor(12 / $numOfCols * 1.22);

            $output .= " <div class=\"container-fluid\">
                         <div class=\"row\">";

            foreach ($user_videos as $row)
            {
                $video_id = $row -> Video_ID;

                $output .= "<div class=\"col-md-".$bootstrapColWidth."\">";
                $output .= "<a href=\"/public/video_details?video=".$video_id."\">
                        <img src=\"http://videodivision.net/assets/images/thumbnails/".$video_id.".jpg\" alt=\"Check Out video details!\" width=\"195\" height=\"280\" border=\"0\">
                        </a> <h5>".substr($row->Title, 0, 22)."</h5>
                        <br><br></div>";

                $counter = $counter + 1;

                if ((($counter % $numOfCols) == 0))
                {
                    $output .= "</div><div class=\"row\">";
                }
            }
            $output .= "</div>
                </div>
                </div>";

        }
        else
        {
            $output = '<h2 align="center" colsapn="5"> You are not subscribed to any Videos!</h2>';
        }


        return view('subscribed_videos',  [
            'output' => $output
        ]);

    }

}
