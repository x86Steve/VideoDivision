<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use DB;

class ContactControllerTest extends TestCase
{

    public function testViewPage()
    {
        $testRequest = $this->get('contact');
        $testRequest->assertViewIs('contact');
    }

    public function testSendMessage()
    {
        $testRequest = $this->post(
            'contact',
            [
                'name' => "Test User",
                'email' => 'test@example.com',
                'message' => "This is a test message sent by a unit test."
            ]
        );

        $testRequest->assertRedirect('/');
    }
}
