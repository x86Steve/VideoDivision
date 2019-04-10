<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use Illuminate\Support\Facades\View;
use DB;
class UserProfileController extends Controller
{
    public function update_avatar(Request $request)
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

            return View::make("profile.profile")
                ->with(array('CurrentUser' => Auth::user()));
        }
    }
    public function index()
    {
        $User_Sub_Video_IDs = DB::table('active_subscriptions')->where('User_ID',Auth::user()->id)->get();
        $User_Sub_Video_Titles = array();

        if (sizeof($User_Sub_Video_IDs) > 0)
            foreach ($User_Sub_Video_IDs as $id)
                $User_Sub_Video_Titles[] = DB::table('video')->where('Video_ID',$id->Video_ID)->first();

        return View::make("profile.profile")
            ->with(array('Video_Titles' => $User_Sub_Video_Titles));
    }
    //
}
