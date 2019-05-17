<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class WatchVideoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessUnsubscribedMovie()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('watch/6');

        $testResponse->assertRedirect('video_details?video=6');
    }

    public function testAccessUnsubscribedShow()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('watch/37/season/1/episode/1');

        $testResponse->assertRedirect('video_details?video=37');
    }

    public function testWatchMovie()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->post(
            'payment',
            [
                'selection' => 1
            ]
        );
        // redirect to profile
        $testResponse->assertRedirect('profile');
        // refresh model
        Auth::user()->refresh();

        //subscribe user
        $testResponse2 = $this->post('video_details?video=6', [
            'postType' => 0,
            'isMovie' => 1,
            'User_ID' => $testUser->id,
            'Video_ID' => 6
        ]);
        $testResponse2->assertViewIs('view_video_details');

        // refresh model
        Auth::user()->refresh();
    }
}
