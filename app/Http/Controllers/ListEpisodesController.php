<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

class ListEpisodesController extends Search\SearchController
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


    }

    //Used to add entries to the database showing the user has subscribed
    function subscribe()
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

}
