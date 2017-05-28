<?php

namespace App\Donation;

use App\Donation as DbDonation;
use App\Donor;

class Charge
{
    /**
     * @var Donor
     */
    private $donor;

    /**
     * @var Donation
     */
    private $donation;

    /**
     * Charge constructor.
     * @param $donor
     */
    public function __construct($donor)
    {
        $this->donor = $donor;
    }

    /**
     * @return \Stripe\Charge
     */
    public function handle()
    {
        $this->addTransactionToDatabase();
        $response = $this->chargeDonor();
        $this->sendEmailReceipt();

        return $response;
    }

    /**
     * We want to add all transactions to the database
     */
    private function addTransactionToDatabase()
    {
        $this->donation = DbDonation::create([
            'donor_id' => $this->donor->id,
            'amount' => request()->json('amount'),
            'is_paying_fees' => request()->json('is_paying_fees'),
            'is_recurring' => request()->json('is_recurring'),
            'designation_id' => request()->json('designation'),
            'mission_support' => request()->json('mission_support'),
            'staff_support' => request()->json('staff_support'),
        ]);
    }

    /**
     * @return \Stripe\Charge
     */
    private function chargeDonor()
    {
        return \Stripe\Charge::create([
            'amount' => request()->json('amount'),
            'currency' => 'usd',
            'customer' => $this->donor->stripe_id,
            'metadata' => [
                'designation' => request()->json('designation'),
                'mission_support' => request()->json('mission_support') ?? 'none',
                'staff_support' => request()->json('staff_support') ?? 'none',
                'is_paying_fees' => request()->json('is_paying_fees')
            ]
        ], ['api_key' => config('services.stripe.secret')]);
    }

    private function sendEmailReceipt()
    {
        $emailReceipt = new DonationReceipt($this->donation);
        $emailReceipt->send();
    }
}