<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use DB;

class UserProfileControllerTest extends TestCase
{
    public function testGoHome()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        $testRequest = $this->get('home');

        $testRequest->assertViewIs('home');
    }
}
