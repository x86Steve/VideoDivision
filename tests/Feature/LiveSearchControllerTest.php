<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use DB;

class LiveSearchControllerTest extends TestCase
{
    public function testLiveSearch()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        // get grid search view
        $testRequest = $this->get('live_search/grid');
        $testRequest->assertViewIs('live_search');

        // get table search view
        $testRequest2 = $this->get('live_search/table');
        $testRequest2->assertViewIs('live_search');

        // get grid search view with a search update
        $testRequest3 = $this->get('live_search/action');
        $testRequest3->assertOk();

        // get table search view with a search update
        $testRequest3 = $this->get('live_search/action2');
        $testRequest3->assertOk();
    }
}
