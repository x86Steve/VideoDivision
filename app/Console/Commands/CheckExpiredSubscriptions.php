<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Faker\Provider\DateTime;

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
        $this->info("Ran!");
        // get the list of subscriptions from the database
        $subscriptions = DB::table('active_subscriptions')->get();
        foreach ($subscriptions as $subscription)
        {
            if ($subscription->Start_Date < DateTime.now()->sub(new DateInterval('P1D')))
            {
                // if the subscription was created more than 1 day ago,
                // remove record from database
                DB::table('active_subscriptions')->delete($subscription->ID);
                $this->output->info("Removed " . $subscription->ID);
            }
        }
    }
}
