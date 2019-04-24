<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Support\Facades\Request;

class ChatController extends Search\SearchController
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


        if (\Auth::check())
        {
            $user_id = \Auth::user()->id;
        }
        else
        {
            redirect('/login');
        }

        if(Input::has('user'))
        {
            $other_id = Input::get ( 'user' );

        }
        else
        {
            return redirect('/inbox');
        }

        DB::table('chat_log')->where(['Receiver_ID' => Auth::user()->id, 'Sender_ID' => $other_id, 'isRead' => '0'])->update(['isRead' => '1']);

        //get messages
            $sMessages = DB::table('chat_log')
                -> where('Sender_ID', '=', "$user_id")
                -> where('Receiver_ID', '=', "$other_id")
                ->get();

            $rMessages = DB::table('chat_log')
                -> where('Sender_ID', '=', "$other_id")
                -> where('Receiver_ID', '=', "$user_id")
                ->get();
        //merge them
        //order them
        $results = $sMessages->merge($rMessages)->sortBy('Time_Sent');

        //format cutely
        $fChat = '';
        $fChat .='    <div class="container">
        <div class="row">
            <div class="col-lg-11 order-lg-1"> <div id="chat_scroll" style="height:450px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">';

        $user_info = DB::table('users')->where('id', '=', "$user_id")->first();
        $other_info = DB::table('users')->where('id', '=', "$other_id")->first();

        $user_img = asset('avatars') . '//' . $user_info->avatar;
        $other_img = asset('avatars') . '//' . $other_info->avatar;

        foreach ($results as $chat)
        {
            //if user sent message have on right and darker
            if($chat->Sender_ID === $user_id)
            {
                $fChat .= '
                          <div class="bg-primary text-white float-right clearfix"  style="width: auto;max-width: 900px">
                          <div style="padding-left: 15px; padding-right: 15px;padding-top: 5px">
                          <p align="left">'.$chat->Message.'
                          <span class="time-left"><small><br>sent at: '.$chat->Time_Sent.'</small></span></p> 
                          </div>
                        </div><div style="clear: both"></div><br>
                        ';
            }
            else //else message was sent by the other person, have it on the left
            {
                $fChat .= '<div class="bg-secondary text-white float-left clearfix" style="width: auto; max-width: 900px">
                            <div style="padding-left: 15px; padding-right: 15px;padding-top: 5px">
                          <p align="left">'.$chat->Message.'
                          <span class="time-left"> <small><br>sent at: '.$chat->Time_Sent.'</small></span></p>
                          </div>
                        </div><div style="clear: both"></div><br>
                        ';
            }

        }

        $fChat .= '</div>';

        return view('chat',['chat'=>$fChat, "receiver_id"=>$other_id,"other_img"=>$other_img, "other_info"=>$other_info]);

    }

    public function remove_add_Friend($friend_id)
    {
        if (helper_UserExist($friend_id))
        {
            if(helper_isFriend($friend_id))
                DB::table('friends')->where(['User_ID' => Auth::user()->id, 'Friend_ID' => $friend_id])->delete();
            else
                DB::table('friends')->insert(['User_ID' => Auth::user()->id, 'Friend_ID' => $friend_id]);

            return redirect("/profile/".helper_GetUsernameById($friend_id));
        }

        return redirect()->back();
    }

    //Used to add entries to the database showing the user has subscribed
    function postHandler()
    {

        $postType = Request::get('postType');

        if (\Auth::check())
        {
            $user_id = \Auth::user()->id;
        }
        else
        {
            $user_id = -1;
        }

        if($postType == 0)
        {

            $User_ID = $user_id;
            $Receiver_ID = Request::get('Receiver_ID');
            $message_text = Request::get('Message');

            $message_text = strip_tags($message_text);



            DB::table('chat_log')->insertGetId(
                ['ID' => 0, 'Sender_ID' => $User_ID, 'Receiver_ID' => $Receiver_ID, 'Message' => $message_text, 'isRead' => 0]);


        }

        return redirect("/chat?user=$Receiver_ID");

    }

    //Used to add entries to the database showing the user has subscribed
    function favorite()
    {

    }



}
