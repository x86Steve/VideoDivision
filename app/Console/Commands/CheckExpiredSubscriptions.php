<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks user subscriptions and removes access to expired subscriptions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get the list of subscriptions from the database
        $subscriptions = DB::table('active_subscriptions')->get();
        foreach ($subscriptions as $subscription)
        {
            // expire after 1 day for a movie
            if ($subscription->IsMovie)
            {
                $deadline = now()->sub('day', 1);
            }
            // expire after 30 days for a show
            else
            {
                $deadline = now()->sub('day', 30);
            }

            // if the subscription duration has passed the deadline,
            // remove the entry from the database
            if (new \DateTime($subscription->Start_Date) < $deadline)
            {
                DB::table('active_subscriptions')->delete($subscription->ID);
                $this->info("Removed user " . $subscription->User_ID . "'s subscription to "
                . $subscription->Video_ID . ". (Subscription ID " . $subscription->ID . ")");
            }
        }
    }
}
