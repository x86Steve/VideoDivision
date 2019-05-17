<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class UserProfileControllerTest extends TestCase
{

    public function testUpdateAvatar()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // generate a fake image to upload
        $image = UploadedFile::fake()->image('avatar.jpg');

        $testResponse = $this->post(
            'profile',
            [
                'avatar' => $image
            ]
        );

        // refresh model
        Auth::user()->refresh();

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // avatar should exist on the disk
        Storage::disk('avatars')->assertExists($testUser->avatar);
    }

    public function testFailToUpdateAvatarWithIncorrectFiletype()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // generate a text file to upload
        $file = UploadedFile::fake()->create('fail.txt');

        $testResponse = $this->post(
            'profile',
            [
                'avatar' => $file
            ]
        );

        // refresh model
        Auth::user()->refresh();

        // response should not have errors
        $testResponse->assertSessionDoesntHaveErrors();

        // file should not exist on the disk
        Storage::disk('avatars')->assertMissing($file->name);
    }

    public function testFailToUpdateAvatarWithNoFile()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->post('profile');

        // response should not have errors
        $testResponse->assertSessionDoesntHaveErrors();
    }

    public function testFailToUpdateAvatarAsGuest()
    {
        // generate a fake image to upload
        $image = UploadedFile::fake()->image('avatar.jpg');

        $testResponse = $this->post(
            'profile',
            [
                'avatar' => $image
            ]
        );

        // response should not have errors
        $testResponse->assertSessionDoesntHaveErrors();

        // should redirect to login
        $testResponse->assertRedirect('login');

        // file should not exist on the disk
        Storage::disk('avatars')->assertMissing($image->name);
    }

    public function testViewOwnProfileNoParam()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('profile');

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // view should have this data and set current user to null
        $testResponse->assertViewHasAll(
            [
                'Video_Titles',
                'recent_activities',
                'CurrentUser' => null,
                'Comments'
            ]
        );
    }

    public function testViewOwnProfileWithSubscriptions()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // add subscription info for the user
        DB::table('active_subscriptions')->insert(
            [
                'Video_ID' => 1,
                'User_ID' => Auth::user()->id,
                'IsMovie' => 1
            ]
        );

        $testResponse = $this->get('profile');

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // view should have this data and set current user to null
        $testResponse->assertViewHasAll(
            [
                'Video_Titles',
                'recent_activities',
                'CurrentUser' => null,
                'Comments'
            ]
        );
    }

    public function testViewOtherProfile()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // create another user so we can view their profile
        $otherUser = $this->CreateTestUser();

        $testResponse = $this->get('profile/' . $otherUser->username);

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // view should have this data
        $testResponse->assertViewHasAll(
            [
                'Video_Titles',
                'recent_activities',
                'Comments'
            ]
        );

        // view should contain the target username
        $testResponse->assertSee($otherUser->username);
    }

    public function testFailToViewNonexistentProfile()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        // create an unused username
        $otherUsername = "nulluser";

        $testResponse = $this->get('profile/' . $otherUsername);

        $testResponse->assertStatus(200);
    }

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

    public function testUpdateProfileWithNewPassword()
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

    public function testFailToUpdateProfileWithNoFirstName()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // Become the user
        $this->actingAs($testUser);

        // send the test request
        $testResponse = $this->from('profile')->post(
            'profile/edit',
            [
                'lastname' => 'LastNameEdited',
                'jobtitle' => 'JobTitleEdited',
            ]
        );

        // should be redirected back to profile
        $testResponse->assertRedirect('profile');

        // response should have no errors
        $testResponse->assertSessionHasErrors();
    }

    public function testFailToUpdateProfileWithIncorrectPassword()
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
                'currentpassword' => 'IncorrectPassword'
            ]
        );

        // should be redirected back to profile/edit
        $testResponse->assertRedirect('profile/edit');

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // database should not have updated user information
        $this->assertDatabaseMissing(
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
    }
}
