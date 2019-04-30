<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Exception;
use Auth;
use Image;
use DB;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function set_comment(Request $request, $PossibleUser)
    {
        $validator = Validator::make($request->all(),
            [
                'comment' => 'required|min:3|max:125'
            ]);

        if ($validator->fails())
        {
            Session::flash('error_comment', $validator->messages()->first());
            return redirect()->back()->withInput($request->input())->withErrors($validator);
        }

        //Insert the comment into the database
        DB::insert('insert into user_comments (userwall_username,commenter_username,comment,time) values (?,?,?,?)', array(isset($PossibleUser) ? $PossibleUser : Auth::user()->username, isset($PossibleUser) ? Auth::user()->username : $PossibleUser  ,$request->input('comment'), now()));

        return redirect()->back();
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'firstname' => 'string|required|regex:/^[\w-]*$/|max:50',
                'lastname' => 'string|required|regex:/^[\w-]*$/|max:50',
                //'email'=> 'nullable|string|email|unique:users',
                'jobtitle' => 'nullable|string|max:50',
                'description' => 'nullable|string|max:250',
                'street' => 'nullable|regex:/([- ,\/0-9a-zA-Z]+)/|max:100',
                'city' => 'nullable|string|max:50',
                'state' => 'nullable|string|regex:/^[\w-]*$/|max:2|min:2',
                'currentpassword' => 'required|string|min:8',
                'newpassword' => 'nullable|min:8|string',
                'newpassword_confirmed' => 'nullable|same:newpassword'
            ]);

        if ($validator->fails()) {
            Session::flash('error_profile_settings', $validator->messages()->first());
            return redirect()->back()->withInput($request->input())->withErrors($validator);
        }

        // Everything at this point is valid, so, now, lets make sure the password they entered is correct before adjusting DB
        if (Hash::check($request->input('currentpassword'),Auth::user()->password))
        {
            $newpassword = $request->input('newpassword_confirmed');

            if (strlen($newpassword) >= 8)
                $newpassword = Hash::make($newpassword);
            else
                $newpassword = Auth::user()->password;

            // Password check passed, insert into database
            DB::table('users')->where('username',Auth::user()->username)
                ->update([
                    'name' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'jobtitle' => $request->input('jobtitle'),
                    'description' => $request->input('description'),
                    'street' => $request->input('street'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'password' => $newpassword
                    ]
                );
            Session::flash('success_msg','Changes have been saved successfully!');
        }
        else
            // Failed password check, alert user.
            Session::flash('error_password','You have supplied an incorrect password!');

        return redirect()->refresh();
    }

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

    private function get_recent_wall_comments($CurrentUser = null)
    {
        $User_Recent_Wall_Comments = DB::table('user_comments')->where('userwall_username',(isset($CurrentUser) ? helper_GetUsernameById($CurrentUser->id) : Auth::user()->username))->limit(5)->orderBy('time','desc')->get();

        return $User_Recent_Wall_Comments;
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
        return View::make("profile.profile")
            ->with(array(
                'Video_Titles' => isset($CurrentUser) ? $this->get_current_subscription_titles($CurrentUser) : $this->get_current_subscription_titles(),
                'recent_activities' => isset($CurrentUser) ? $this->get_recent_activity($CurrentUser) : $this->get_recent_activity(),
                'CurrentUser' => $CurrentUser,
                'Comments' => $this->get_recent_wall_comments($CurrentUser)));
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
