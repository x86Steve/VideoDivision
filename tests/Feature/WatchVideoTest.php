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
    public function accessUnsubscribedMovie()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('watch/6');

        $testResponse->assertViewIs('view_video_details');
    }

    public function accessUnsubscribedShow()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('watch/37/season/1/episode/1');

        $testResponse->assertViewIs('view_video_details');
    }

    public function watchMovie()
    {

    }
}
