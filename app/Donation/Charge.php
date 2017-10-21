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

        // TODO: Send email to Andrew and others if needed


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
            'general_comments' => request()->json('general_comments'),
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
                'designation' => $this->donation->designation->name,
                'general_comments' => request()->json('general_comments') ?? 'none',
                'mission_support' => request()->json('mission_support') ?? 'none',
                'staff_support' => request()->json('staff_support') ?? 'none',
                'is_paying_fees' => request()->json('is_paying_fees')
            ]
        ], ['api_key' => config('services.stripe.secret')]);
    }

    /**
     * Send an email receipt the the donor.
     */
    private function sendEmailReceipt()
    {
        $emailReceipt = new DonationReceipt($this->donation);
        $emailReceipt->send();
    }
}