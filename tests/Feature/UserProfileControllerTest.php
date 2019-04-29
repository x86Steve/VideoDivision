<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileControllerTest extends TestCase
{

    // Laravel provides functionality to automatically reset the database after testing
    // but i can't be assed to learn how migrations work so i'm leaving this commented out for now
    // uncomment at own risk

    //use RefreshDatabase;

    public function testUpdateProfile()
    {
        // create a test user
        $testUsername = 'user' . str_random(4);
        $testUser = factory(User::class)->create(
            [
                'password' => Hash::make('TestCurrentPassword'),
                'lastname' => 'User',
                'username' => $testUsername
            ]
        );

        // Become the user
        $this->actingAs($testUser);

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
                'currentpassword' => 'TestCurrentPassword'
            ]
        );

        // should be redirected back to profile/edit
        $testResponse->assertRedirect('profile/edit');

        // response should have no errors
        $testResponse->assertSessionHasNoErrors();

        // check if database has updated user information
        $this->assertDatabaseHas(
            'users',
            [
                'username' => $testUsername,
                'name' => 'FirstNameEdited',
                'lastname' => 'LastNameEdited',
                'jobtitle' => 'JobTitleEdited',
                'description' => 'DescriptionEdited',
                'street' => 'StreetEdited',
                'city' => 'CityEdited',
                'state' => 'CA'
            ]);
    }
    
}
