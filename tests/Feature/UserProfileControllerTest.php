<?php

namespace Tests\Feature;

use Tests\TestCase;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserProfileControllerTest extends TestCase
{

    public function testPostCommentOnOwnProfile()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $username = $testUser->username;
        $profileMessage = 'This is my profile 😉';

        $testResponse = $this->from('profile/' . $username)->post(
            'profile/' . $username . '/comment',
            [
                'comment' => $profileMessage
            ]
        );

        // should be redirected to user's profile
        $testResponse->assertRedirect('profile/' . $username);

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // database should have new post
        $this->assertDatabaseHas(
            'user_comments',
            [
                'userwall_username' => $username,
                'commenter_username' => $username,
                'comment' => $profileMessage
            ]
        );
    }

    public function testPostCommentOnOtherProfile()
    {
        // create two test users
        $testUser = $this->CreateTestUser();
        $otherUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $myUsername = $testUser->username;
        $otherUsername = $otherUser->username;
        $profileMessage = 'This is someone else\'s profile 😮';

        $testResponse = $this->from('profile/' . $otherUsername)->post(
            'profile/' . $otherUsername . '/comment',
            [
                'comment' => $profileMessage
            ]
        );

        // should be redirected to other user's profile
        $testResponse->assertRedirect('profile/' . $otherUsername);

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // database should have new post
        $this->assertDatabaseHas(
            'user_comments',
            [
                'userwall_username' => $otherUsername,
                'commenter_username' => $myUsername,
                'comment' => $profileMessage
            ]
        );
    }

    public function testFailToPostShortMessage()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $username = $testUser->username;
        $profileMessage = 'aa';

        $testResponse = $this->from('profile/' . $username)->post(
            'profile/' . $username . '/comment',
            [
                'comment' => $profileMessage
            ]
        );

        // should be redirected to user's profile
        $testResponse->assertRedirect('profile/' . $username);

        // response should have errors
        $testResponse->assertSessionHasErrors();

        // database should not have new post
        $this->assertDatabaseMissing(
            'user_comments',
            [
                'userwall_username' => $username,
                'commenter_username' => $username,
                'comment' => $profileMessage
            ]
        );
    }

    public function testUpdateProfile()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        // send the test request
        $testResponse = $this->from('profile')->post(
            'profile/edit',
            [
                'firstname' => 'FirstNameEdited',
                'lastname' => 'LastNameEdited',
                'jobtitle' => 'JobTitleEdited',
                'description' => 'DescriptionEdited',
                'street' => 'StreetEdited',
                'city' => 'CityEdited',
                'state' => 'CA',
                'currentpassword' => 'TestPassword',
                'newpassword' => 'NewPassword',
                'newpassword_confirmed' => 'NewPassword'
            ]
        );

        // should be redirected back to profile/edit
        $testResponse->assertRedirect('profile/edit');

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // database should have updated user information
        $this->assertDatabaseHas(
            'users',
            [
                'username' => $testUser->username,
                'name' => 'FirstNameEdited',
                'lastname' => 'LastNameEdited',
                'jobtitle' => 'JobTitleEdited',
                'description' => 'DescriptionEdited',
                'street' => 'StreetEdited',
                'city' => 'CityEdited',
                'state' => 'CA'
            ]
        );

        // check updated password
        Auth::user()->refresh();
        $this->assertTrue(Hash::check('NewPassword', Auth::user()->password));
    }
}
