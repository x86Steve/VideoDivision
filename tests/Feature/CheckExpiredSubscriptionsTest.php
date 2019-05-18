<?php

namespace Tests\Feature;

use Tests\TestCase;
use Artisan;
use Auth;
use DB;

class CheckExpiredSubscriptionsTest extends TestCase
{
    
    public function testRunCommand()
    {
        Artisan::call('subscriptions:check');

        $this->assertTrue(true);
    }

}
