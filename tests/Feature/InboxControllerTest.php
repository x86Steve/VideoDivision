<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class InboxControllerTest extends TestCase
{

    public function testInboxView()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // going to inbox page
        $testResponse = $this->get('inbox');
        $testResponse->assertViewIs('inbox');

        // refresh model
        Auth::user()->refresh();

        // testing inbox clear
        $testResponse2 = $this->get('inbox/clear');
        $testResponse2->assertRedirect('inbox');
    }
}
