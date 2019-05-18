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
                'Sender_ID' => 7,
                'Receiver_ID' => $testUser->id,
                'Message' => 'Test Message',
                'Time_Sent' => now(),
                'isRead' => 0
            ]
        );

        // going to chat
        $testResponse = $this->get('chat?user=7');
        $testResponse->assertViewIs('chat');

        // refresh model
        Auth::user()->refresh();
    }

    public function testAddRemoveAFriend()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // adding a friend on their profile page
        $testResponse = $this->get('/chat/addremove/7');
        // check for redirect
        $testResponse->assertRedirect('profile/SydneyHo');
        // refresh model
        Auth::user()->refresh();

        // check database

        $friendFlag = DB::table('friends')
            ->where('User_ID', '=', "$testUser->id")
            ->where('Friend_ID', '=', "7")
            ->select('friends.*')
            ->first();

        assert($friendFlag);

        // refresh model
        Auth::user()->refresh();

        // delete a friend on their profile page

        $testResponse2 = $this->get('chat/addremove/7');
        $friendFlag = DB::table('friends')
            ->where('User_ID', '=', "$testUser->id")
            ->where('Friend_ID', '=', "7")
            ->select('friends.*')
            ->first();

        assert(!$friendFlag);
        // check for redirect
        $testResponse2->assertRedirect('profile/SydneyHo');
        // refresh model
        Auth::user()->refresh();
    }

    public function testSendMessage()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->post('chat', [
            'message_text' => 'Testing message sent',
            'postType' => 0,
            'User_ID' => $testUser->id,
            'Receiver_ID' => 7
            ]);
        $testResponse->assertRedirect('chat?user=7');
    }
}
