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

        $testFriend = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // add a test message to the user's profile
        DB::table('chat_log')->insert(
            [
                'Sender_ID' => $testFriend->id,
                'Receiver_ID' => $testUser->id,
                'Message' => 'Test Message',
                'Time_Sent' => now(),
                'isRead' => 0
            ]
        );

        // add friend
        DB::table('friends')->insert(
            [
                'User_ID' => $testUser->id,
                'Friend_ID' => $testFriend->id
            ]
        );

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
