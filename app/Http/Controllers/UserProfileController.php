<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Auth;
use Image;
use Illuminate\Support\Facades\View;
use DB;

class UserProfileController extends Controller
{
    private function grab_target_user_table($username)
    {
        $user = null;
        
        try
        {
            $user = DB::table('users')->where('username', $username)->first();
        }
        catch(Exception $ex)
        {
            abort(404);
        }

        return $user;
    }

    private function get_recent_activity($CurrentUser = null)
    {
        $User_Recent_Activity_Table = DB::table('recent_activity')->where('user_id',(isset($CurrentUser) ? $CurrentUser->id : Auth::user()->id))->select(array('entry','created_at'))->limit(5)->orderBy('created_at','desc')->get();

        return $User_Recent_Activity_Table;
    }

    private function get_current_subscription_titles($CurrentUser = null)
    {
        $User_Sub_Video_IDs = DB::table('active_subscriptions')->where('User_ID',(isset($CurrentUser) ? $CurrentUser->id : Auth::user()->id))->get();
        $User_Sub_Video_Titles = array();

        if (sizeof($User_Sub_Video_IDs) > 0)
            foreach ($User_Sub_Video_IDs as $id)
                $User_Sub_Video_Titles[] = DB::table('Video')->where('Video_ID', $id->Video_ID)->first();

            return $User_Sub_Video_Titles;
    }
  
    public function update_avatar(Request $request)
    {
        if (Auth::guest())
            return redirect()->route('login');

        try
        {
            // Handles user upload of avatar
            if($request->hasFile('avatar'))
            {
                $avatar = $request->file('avatar');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300,300)->save(public_path('/avatars/' . $filename));

                $user = Auth::user();
                $user->avatar = $filename;
                $user->save();

                return $this->index();
            }
        }

        catch (Exception $ex)
        {
            return $this->index()->with("error_msg","We only accept: JPG, PNG, GIF or WebP files!");
        }


        return $this->index();
    }
    public function index($CurrentUser = null)
    {
        if (Auth::guest())
            return redirect()->route('login');

        return View::make("profile.profile")
            ->with(array(
                'Video_Titles' => isset($CurrentUser) ? $this->get_current_subscription_titles($CurrentUser) : $this->get_current_subscription_titles(),
                'recent_activities' => isset($CurrentUser) ? $this->get_recent_activity($CurrentUser) : $this->get_recent_activity(),
                'CurrentUser' => $CurrentUser));
    }

    public function viewprofile($username)
    {
        // Is the user profile you're visiting yourself?
        if (strtolower(Auth::user()->username) == strtolower($username))
            return $this->index();

        return $this->index($this->grab_target_user_table($username));
    }
    //
}
