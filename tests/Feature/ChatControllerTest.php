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

    public function testAddRemoveAFriend()
    {
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // adding a friend on their profile page
        $testResponse = $this->get('/chat/addremove/1');
        // check for redirect
        $testResponse->assertRedirect('profile/JorgeAvalos');
        // refresh model
        Auth::user()->refresh();

        // check database

        $friendFlag = DB::table('friends')
            ->where('User_ID', '=', "$testUser->id")
            ->where('Friend_ID', '=', "1")
            ->select('friends.*')
            ->first();

        assert($friendFlag);

        // refresh model
        Auth::user()->refresh();

        // delete a friend on their profile page

        $testResponse2 = $this->get('chat/addremove/1');
        $friendFlag = DB::table('friends')
            ->where('User_ID', '=', "$testUser->id")
            ->where('Friend_ID', '=', "1")
            ->select('friends.*')
            ->first();

        assert(!$friendFlag);
        // check for redirect
        $testResponse2->assertRedirect('profile/JorgeAvalos');
        // refresh model
        Auth::user()->refresh();
    }
}
