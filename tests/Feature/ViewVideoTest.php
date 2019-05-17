<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class ViewVideoTest extends TestCase
{

    public function testViewVideoDetails()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // get details page for video id 1
        $testResponse = $this->get('video_details?video=1');

        // test that the correct view was returned
        $testResponse->assertViewIs('view_video_details');

        // test that the view contains important information
        $testResponse->assertViewHasAll(
            [
                'User_ID' => Auth::user()->id,
                'isPaid' => false,
                'isSubbed' => false,
                'isMovie' => true,
                'isFav' => false,
                'directors',
                'cast',
                'genres'
            ]
        );
    }

    public function testFavoriteMovie()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        //favorite video id 1
        $testResponse = $this->post(
            'video_details?video=1',
            [
                'postType' => 1,
                'isMovie' => 1,
                'User_ID' => Auth::user()->id,
                'Video_ID' => 1
            ]
        );

        // test that the correct view was returned
        //$testResponse->assertRedirect('video_details');
        $testResponse->assertViewIs('view_video_details');
    }

    public function testUnfavoriteMovie()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        //favorite video id 1
        $testResponse = $this->post(
            'video_details?video=1',
            [
                'postType' => 2,
                'isMovie' => 1,
                'User_ID' => Auth::user()->id,
                'Video_ID' => 1
            ]
        );

        // test that the correct view was returned
        //$testResponse->assertRedirect('video_details');
        $testResponse->assertViewIs('view_video_details');
    }

    public function testGetMyVideosView()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // subscribe to a movie
        $testResponse = $this->post(
            'video_details',
            [
                'postType' => 0,
                'isMovie' => 1,
                'User_ID' => Auth::user()->id,
                'Video_ID' => 1
            ]
        );

        // get the my_videos view
        $testResponse = $this->get('my_videos');

        // test that the correct view was returned
        $testResponse->assertViewIs('subscribed_videos');

        // check that the view has output
        $testResponse->assertViewHas('output');
    }

    public function testSubscribeToMovie()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        //subscribe user to video id 1
        $testResponse = $this->post(
            'video_details?video=1',
            [
                'postType' => 0,
                'isMovie' => 1,
                'User_ID' => Auth::user()->id,
                'Video_ID' => 1
            ]
        );

        // test that the correct view was returned
        $testResponse->assertViewIs('view_video_details');
        //$testResponse->assertRedirect('video_details');
    }

}
