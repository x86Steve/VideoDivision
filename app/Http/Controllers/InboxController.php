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
     * {
     * $details = $Video_ID;
     * return view('view_video_details', ['details' =>$details]);
     * }
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
        } else {
            $user_id = -1;
        }

        $output = '';
        $testCount = 0;

        //get recent chats

        $chats = DB::table('chat_log')
            ->where('Sender_ID', '=', "$user_id")
            ->orwhere('Receiver_ID', '=', "$user_id")
            ->select('chat_log.*')
            ->orderBy('Time_Sent', 'desc')
            ->get();

        //put them in a list

        $other_Ids = [];
        $cur = 0;

        foreach ($chats as $chat) {
            if ($chat->Sender_ID === $user_id) {
                $otherID = $chat->Receiver_ID;
            } else {
                $otherID = $chat->Sender_ID;
            }

            if (!in_array($otherID, $other_Ids)) {
                $other_Ids[$cur] = $otherID;
                $cur += $cur + 1;
            }

            if ($cur > 100) {
                break;
            }
        }


        $output .= "<div class=\"container\">";
        foreach ($other_Ids as $other_user) {
            $user_info = DB::table('users')->where('id', '=', "$other_user")->first();
            $most_recent = DB::table('chat_log')
                ->where('Sender_ID', '=', "$other_user")
                ->where('Receiver_ID', '=', "$user_id")
                ->orWhere('Sender_ID', '=', "$user_id")
                ->where('Receiver_ID', '=', "$other_user")
                ->orderBy('Time_Sent', 'desc')
                ->first();

            if (is_object($user_info)) {
                $friend_unread_messages = helper_GetNewMessageCount_By_Sender($other_user);

                if ($friend_unread_messages <= 0)
                    $friend_unread_messages = "";

                $user_img = asset('avatars') . '//' . $user_info->avatar;

                $format_recent = '';
                if (is_object($most_recent)) {
                    $message = $most_recent->Message;
                    if (strlen($message) > 140) {
                        $format_recent = substr($message, 0, 140) . "...";
                    } else {
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
                                     " . $format_recent . "
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

        $friends = DB::table('friends')->where('User_ID', '=', "$user_id")->paginate(50);

        $message_friend = '';

        foreach ($friends as $friend) {
            $friend_info = DB::table('users')->where('id', '=', "$friend->Friend_ID")->first();

            if (is_object($friend_info)) {
                $friend_img = asset('avatars') . '//' . $friend_info->avatar;
                $friend_name = $friend_info->username;
                $friend_unread_messages = helper_GetNewMessageCount_By_Sender($friend_info->id);

                $message_friend .= '
                <div class="row">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="float" >
                          <img src=' . $friend_img . ' alt="Friend Image" width="40" height="40" border="0">
                          <br> 
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="float" >
                        <a href="/public/chat?user=' . $friend_info->id . '">
                                 <button type="submit" class="btn btn-dark btn-sm">Chat</button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $friend_name . ' <span class="badge badge-success">' . ($friend_unread_messages > 0 ? $friend_unread_messages : '') . '</span>
                
                   
                </a>
                     </div>
                </div><br>';
            }

        }
        //$output .= "<h4><strong>From: </strong>".$chat->Sender_ID."</h4><br>".$chat->Message."<br>";


        //add a button to access that chat


        return view('inbox', ['Messages' => $output, 'Sidebar' => $message_friend]);

    }

    //Used to add entries to the database showing the user has subscribed
    function postHandler()
    {
        //Clear messages

        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = -1;
        }

        ####CLEAR ALL NOTIFICATIONS HERE ##################################################################

        return $this->getView();
    }
}
