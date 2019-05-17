<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class ListEpisodesControllerTest extends TestCase
{
    public function testAllEpisodesPage()
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

        // test redirect when not subbed yet
        $testResponse5 = $this->get('view/36');
        $testResponse5->assertRedirect('video_details?video=36');

        // subscribe user
        $testResponse2 = $this->post('video_details?video=36', [
            'postType' => 0,
            'isMovie' => 0,
            'User_ID' => $testUser->id,
            'Video_ID' => 36
        ]);
        $testResponse2->assertViewIs('view_video_details');

        // refresh model
        Auth::user()->refresh();

        // go to all episodes page
        $testResponse3 = $this->get('view/36');
        $testResponse3->assertViewIs('all_episodes');

        //watch season 2 episode 1
        $testResponse4 = $this->get('watch/36/season/2/episode/1');
        $testResponse4->assertViewIs('watch');
    }

}
