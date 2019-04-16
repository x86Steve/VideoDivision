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
