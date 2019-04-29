<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Post;
use DB;
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function posts()

    {
        $posts = Post::all();

        return view('posts',compact('posts'));

    }

    public function show($id)

    {

        DB::table('ratings')
            ->where('user_id', Auth::user()->id)
            ->update(['avatar' => Auth::user()->avatar]);

        $post = Post::find($id);

        $review = DB::table('ratings')->where("rateable_id","=","$id")->pluck('review','username');
        $user_id = DB::table('ratings')->where("rateable_id","=","$id")->pluck('user_id');
        $full_query = DB::table('ratings')->where('rateable_id', "=","$id")->select('username','review','created_at','avatar','user_id')->get();

        return view('postsShow',compact('post'), compact('review','user_id','full_query' ));

    }

    public function postPost(Request $request)

    {

        $id = $request->movieID;
        $userid=$request->userID;
        $user_id = DB::table('ratings')->where("rateable_id","=","$id")->pluck('user_id');

        $txt=0;
        foreach($user_id as $user_id)
        {
            if ($user_id ==Auth::user()->id)
            {
                $txt=1;
            }
        }

        if($txt==0)
        {
            request()->validate(['rate' => 'required']);
            request()->validate(['message' => 'required']);
            $post = Post::find($request->id);
            $rating = new \willvincent\Rateable\Rating;
            $rating->rating = $request->rate;
            $rating->user_id = auth()->user()->id;
            $rating->review = $request->message;
            $rating->username = Auth::user()->username;
            $rating->avatar = Auth::user()->avatar;
            $post->ratings()->save($rating);
        }
        elseif($txt==1)
        {
            request()->validate(['rerate' => 'required']);
            request()->validate(['remessage' => 'required']);

            DB::table('ratings')
                ->where('user_id', $userid)
                ->update(['review' => $request->remessage]);

            DB::table('ratings')
                ->where('user_id', $userid)
                ->update(['rating' => $request->rerate]);
            DB::table('ratings')
                ->where('user_id', $userid)
                ->update(['avatar' => Auth::user()->avatar]);

        }

        return redirect()->route("posts");

    }
}
