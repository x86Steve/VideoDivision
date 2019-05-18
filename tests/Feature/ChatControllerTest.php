<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class ChatControllerTest extends TestCase
{

    public function testChatView()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // add a test message to the user's profile
        DB::table('chat_log')->insert(
            [
                'Sender_ID' => $testUser->id + 1,
                'Receiver_ID' => $testUser->id,
                'Message' => 'Test Message',
                'Time_Sent' => now(),
                'isRead' => 0
            ]
        );

        // going to chat
        $testResponse = $this->get('chat?user=1');
        $testResponse->assertViewIs('chat');

        // refresh model
        Auth::user()->refresh();
    }
}
