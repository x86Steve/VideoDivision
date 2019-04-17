<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Post;
use DB;
class PostController extends Controller
{
    public function posts()

    {
        $posts = Post::all();

        return view('posts',compact('posts'));

    }



    public function show($id)

    {
        if (Auth::guest())
            return redirect()->route('login');





        $post = Post::find($id);

        $review = DB::table('ratings')->where("rateable_id","=","$id")->pluck('review','username');
        //$username=DB::table('ratings')->where("rateable_id","=","$id")->pluck('username');
        $user_id = DB::table('ratings')->where("rateable_id","=","$id")->pluck('user_id');

        $full_query = DB::table('ratings')->where('rateable_id', "=","$id")->pluck('username','review')->get();

        return view('postsShow',compact('post'), compact('review','user_id','full_query' ));

    }



    public function postPost(Request $request)

    {

        if (Auth::guest())
            return redirect()->route('login');


        request()->validate(['rate' => 'required']);

        $post = Post::find($request->id);


        $rating = new \willvincent\Rateable\Rating;

        $rating->rating = $request->rate;

        $rating->user_id = auth()->user()->id;

        $rating->review= $request->message;

        $rating->username=Auth::user()->username;

        $post->ratings()->save($rating);



        return redirect()->route("posts");

    }
}
