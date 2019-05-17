<?php

namespace Tests\Feature;

use Tests\TestCase;
use Auth;
use DB;

class AdminToolsControllerTest extends TestCase
{
    public function testViewPageAsAdmin()
    {
        // create a test admin
        $testUser = $this->CreateTestAdmin();

        // Become the user
        $this->actingAs($testUser);

        $testRequest = $this->get('admin');

        $testRequest->assertViewIs('admin');
    }

    public function testFailToViewPageAsNonAdmin()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        $testRequest = $this->get('admin');
        $testRequest->assertRedirect('home');
    }

}
