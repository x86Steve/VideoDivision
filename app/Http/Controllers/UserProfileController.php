<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\View;

class UserProfileController extends Controller
{
    public function index()
    {

        $UserName = Auth::user()->name;
        $UserInfo = DB::table("users")->where('name','=', $UserName)->get();
        $SubscriberStatus =  $UserInfo[0]->isPaid;

        return View::make("profile.profile")
            ->with("username",$UserName)
            ->with("subscriberstatus", $SubscriberStatus);
    }
    //
}
