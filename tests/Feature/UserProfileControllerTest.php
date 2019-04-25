<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileControllerTest extends TestCase
{

    public function testUpdateProfile()
    {
        Request $testRequest = Request::create('profile/edit', 'POST', 
        [
            'firstname' => 'TestFirstName',
            'lastname' => 'TestLastName',
            'jobtitle' => 'TestJobTitle',
            'description' => 'TestDescription',
            'street' => 'TestStreet',
            'city' => 'TestCity',
            'state' => 'TestState',
            'currentpassword' => 'TestCurrentPassword'
        ]
    );

    // assertViewHas? assertSessionHas?
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
