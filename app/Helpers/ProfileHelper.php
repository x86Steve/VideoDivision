<?php

if (!function_exists('ActivityEntry'))
{
    function ActivityEntry(string $entry)
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


