<?php

if (!function_exists('helper_isAdmin'))
{
    function helper_isAdmin($username)
    {
        return (DB::table('users')->where('username',$username)->first()->isAdmin ? TRUE : FALSE);
    }
}

if (!function_exists('helper_isMovie'))
{
    function helper_isMovie($video_ID)
    {
        return (DB::table('video')->where('Video_ID',$video_ID)->first()->IsMovie ? TRUE : FALSE);
    }
}

if (!function_exists('helper_activityEntry'))
{
    function helper_activityEntry(string $entry)
    {
        DB::insert('insert into recent_activity (user_id,entry,created_at) values (?,?,?)', array(Auth::user()->id, $entry, now()));
    }
}

if (!function_exists('helper_GetMovieTitleByID'))
{
    function helper_GetMovieTitleByID($id)
    {
        return DB::select('select `Title` from `Video` where `Video_ID` =:id;',array('id' => $id))[0]->Title;
    }
}

if (!function_exists('helper_GetNewMessagesCount'))
{
    function helper_GetNewMessagesCount()
    {
        return sizeof(DB::table('chat_log')->where(
            ['Receiver_ID' => Auth::user()->id,
                'isRead' => '0'])->pluck('isRead'));
    }
}

if (!function_exists('helper_GetNewMessageCount_By_Sender'))
{
    function helper_GetNewMessageCount_By_Sender($sender_id)
    {
        return sizeof(DB::table('chat_log')->where(
            ['Receiver_ID' => Auth::user()->id,
                'Sender_ID' => $sender_id,
                'isRead' => '0'])->pluck('isRead'));
    }
}

if (!function_exists('helper_isFriend'))
{
    function helper_isFriend($friend_id)
    {
        return sizeof(DB::table('friends')->where(
            ['User_ID' => Auth::user()->id,
                'Friend_ID' => $friend_id])->get()) > 0 ? TRUE : FALSE;
    }
}

if (!function_exists('helper_GetUsernameById'))
{
    function helper_GetUsernameById($id)
    {
        return DB::table('users')->where('id',$id)->first()->username;
    }
}

if (!function_exists('helper_UserExist'))
{
    function helper_UserExist($id)
    {
        return sizeof(DB::table('users')->where('id',$id)->get()) > 0 ? TRUE : FALSE;
    }
}


