<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Auth;
use DB;

class PaymentControllerTest extends TestCase
{
    public function testGetRedirectedAsGuest()
    {
        $testResponse = $this->get('payment');

        $testResponse->assertRedirect('login');
    }

    public function testViewPaymentPage()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->get('payment');

        $testResponse->assertViewIs('payment');
    }

    public function testSubscribeAndUnsubscribe()
    {
        // create a test user
        $testUser = $this->CreateTestUser();

        // become the user
        $this->actingAs($testUser);

        $testResponse = $this->post(
            'payment',
            [
                'selection' => 1
            ]
        );

        // redirect to profile
        $testResponse->assertRedirect('profile');

        // refresh model
        Auth::user()->refresh();

        // user should be subscribed
        $this->assertTrue(Auth::user()->isPaid == 1);

        $testResponse2 = $this->post(
            'payment',
            [
                'selection' => 0
            ]
        );

        // redirect to profile
        $testResponse2->assertRedirect('profile');

        // refresh model
        Auth::user()->refresh();

        // user should be unsubscribed
        $this->assertTrue(Auth::user()->isPaid == 0);
    }

}
