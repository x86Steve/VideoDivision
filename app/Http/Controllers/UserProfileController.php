<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use Illuminate\Support\Facades\View;

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
        return View::make("profile.profile");
           // ->with(array('CurrentUser' => Auth::user()));
    }
    //
}
