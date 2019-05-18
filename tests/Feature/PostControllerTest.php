<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class PostControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostShow()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        // testing post get
        $testResponse = $this->get('posts');
        $testResponse->assertViewIs('posts');

        // testing postPosts
        $testResponse2 = $this->post('posts', [
            'id' => 1,
            'userID' => $testUser->id
        ]);
        $testResponse2->assertRedirect('posts');

        // refresh model
        Auth::user()->refresh();

        // testing post view page of a specific video
        $testResponse3 = $this->get('posts/1');
        $testResponse3->assertViewIs('postsShow');
    }
}
