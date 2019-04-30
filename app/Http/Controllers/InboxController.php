<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;


class InboxController extends Search\SearchController
{
    //I cant remember if this function is ever called but here it is.
    /**function viewDetails($Video_ID)
    {
        $details = $Video_ID;
        return view('view_video_details', ['details' =>$details]);
    }
     * **/

    public function __construct()
    {
        $this->middleware('auth');
    }

    //Used to see video details page
    function getView()
    {
        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        }
        else
        {
            $user_id = -1;
        }

        $output = '';
        $testCount = 0;

        //get recent chats

        $chats = DB::table('chat_log')
            ->where('Sender_ID', '=' , "$user_id")
            ->orwhere('Receiver_ID', '=' , "$user_id")
            ->select('chat_log.*')
            ->orderBy('Time_Sent', 'desc')
            ->get();

        //put them in a list

        $other_Ids = [];
        $cur = 0;

        foreach ($chats as $chat)
        {
            if($chat->Sender_ID === $user_id)
            {
                $otherID = $chat->Receiver_ID;
            }
            else
            {
                $otherID = $chat->Sender_ID;
            }

            if(!in_array($otherID, $other_Ids))
            {
                $other_Ids[$cur] = $otherID;
                $cur += $cur + 1;
            }

            if($cur >100){break;}
        }


        $output .= "<div class=\"container\">";
        foreach ($other_Ids as $other_user)
        {
            $user_info = DB::table('users')->where('id', '=', "$other_user")->first();
            $most_recent = DB::table('chat_log')
                ->where('Sender_ID', '=', "$other_user")
                ->where('Receiver_ID', '=', "$user_id")
                ->orWhere('Sender_ID', '=', "$user_id")
                ->where('Receiver_ID', '=', "$other_user")
                ->orderBy('Time_Sent', 'desc')
                ->first();

            if(is_object($user_info))
            {
                $friend_unread_messages = helper_GetNewMessageCount_By_Sender($other_user);

                if ($friend_unread_messages <= 0)
                    $friend_unread_messages = "";

                $user_img = asset('avatars') . '//' . $user_info->avatar;

                $format_recent = '';
                if(is_object($most_recent))
                {
                    $message = $most_recent->Message;
                    if(strlen($message)>140)
                    {
                        $format_recent = substr($message, 0 , 140)."...";
                    }
                    else
                    {
                        $format_recent = $message;
                    }
                }

                $output .= "
                              <div class=\"row\">
                                <div class=\"col-sm-2\">
                                  <img src=$user_img alt=\"User Image\" width=\"95\" height=\"95\" border=\"0\">
                                  <br> $user_info->username <span class=\"badge badge-success\"> $friend_unread_messages </span>
                                </div>
                                <div class=\"col-sm\">
                                     ".$format_recent."
                                </div>
                                <div class=\"col-sm\">
                                     <br><a href=\"/public/chat?user=" . $other_user . "\">
                                     <button type=\"submit\" class=\"btn btn-dark\">View Chat</button>
                                     </a>
                                </div>
                             </div>
                             <br>
                            ";
            }
        }
        $output .= "</div>";

        $friends = DB::table('friends')->where('User_ID','=',"$user_id")->paginate(50);

        $message_friend = '';

        foreach($friends as $friend)
        {
            $friend_info = DB::table('users')->where('id', '=', "$friend->Friend_ID")->first();

            if(is_object($friend_info)) {
                $friend_img = asset('avatars') . '//' . $friend_info->avatar;
                $friend_name = $friend_info->username;
                $friend_unread_messages = helper_GetNewMessageCount_By_Sender($friend_info->id);

                $message_friend .= '
                <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="float" >
                          <img src='.$friend_img.' alt="Friend Image" width="40" height="40" border="0">
                          <br> 
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="float" >
                        <a href="/public/chat?user=' . $friend_info ->id . '">
                                 <button type="submit" class="btn btn-dark btn-sm">Chat</button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$friend_name.' <span class="badge badge-success">'. ($friend_unread_messages > 0 ? $friend_unread_messages : '') .'</span>
                
                   
                </a>
                     </div>
                </div><br>';
            }

        }
        //$output .= "<h4><strong>From: </strong>".$chat->Sender_ID."</h4><br>".$chat->Message."<br>";



        //add a button to access that chat


        return view('inbox', ['Messages'=>$output, 'Sidebar'=>$message_friend]);

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
