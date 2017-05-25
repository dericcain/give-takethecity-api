<?php

namespace App\Donation;

use App\Donation as DbDonation;
use App\RecurringDonation;
use Stripe\Charge as StripeCharge;

class RecurringDonationCharge
{
    /**
     * @var RecurringDonation
     */
    private $recurringDonation;

    /**
     * RecurringDonationCharge constructor.
     * @param $recurringDonation
     */
    public function __construct(RecurringDonation $recurringDonation)
    {
        $this->recurringDonation = $recurringDonation;
        $this->donor = $this->recurringDonation->donor;
    }

    public function charge()
    {
        $this->chargeDonor();
        $this->addTransactionToDatabase();
        $this->addMonthToRecurringDonation();
    }

    /**
     * @return \Stripe\Charge
     */
    private function chargeDonor()
    {
        return StripeCharge::create([
            'amount' => $this->recurringDonation->amount,
            'currency' => 'usd',
            'customer' => $this->donor->stripe_id,
            'metadata' => [
                'designation' => $this->recurringDonation->designation,
                'mission_support' => $this->recurringDonation->mission_support,
                'staff_support' => $this->recurringDonation->staff_support,
                'RECURRING' => true
            ]
        ], ['api_key' => config('services.stripe.secret')]);
    }

    /**
     * We want to add all transactions to the database
     */
    private function addTransactionToDatabase()
    {
        DbDonation::create([
            'donor_id' => $this->donor->id,
            'amount' => $this->recurringDonation->amount,
            'is_paying_fees' => $this->recurringDonation->is_paying_fees,
            'is_recurring' => true,
            'designation_id' => $this->recurringDonation->designation_id,
            'mission_support' => $this->recurringDonation->mission_support,
            'staff_support' => $this->recurringDonation->staff_support,
        ]);
    }

    /**
     * We nee to add a month to a recurring donation so that it wil be charged next month.
     */
    private function addMonthToRecurringDonation()
    {
        $this->recurringDonation->updateNextDonationOn();
    }
}