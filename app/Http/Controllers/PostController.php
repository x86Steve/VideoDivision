<?php

namespace App\Http\Controllers;

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

        $post = Post::find($id);
        $review = DB::table('ratings')->where("rateable_id","=","$id")->pluck('review');
        return view('postsShow',compact('post'), compact('review' ));

    }



    public function postPost(Request $request)

    {


        request()->validate(['rate' => 'required']);

        $post = Post::find($request->id);


        $rating = new \willvincent\Rateable\Rating;

        $rating->rating = $request->rate;

        $rating->user_id = auth()->user()->id;

        $rating->review= $request->message;

        $post->ratings()->save($rating);



        return redirect()->route("posts");

    }
}
