<?php

namespace App\Console\Commands;

use App\RecurringDonation;
use Illuminate\Console\Command;

class ChargeRecurringDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:charge-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This charges recurring donations for the day.';

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
        $this->chargeRecurringDonations();
    }

    /**
     * Iterate through all of the recurring donations for the day and charge them.
     */
    private function chargeRecurringDonations()
    {
        $recurringDonations = RecurringDonation::needChargingToday();

        if ($recurringDonations->isNotEmpty()) {
            $recurringDonations->each->charge();
        }
    }
}
