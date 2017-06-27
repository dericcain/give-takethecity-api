<?php

namespace App\Donation;

use App\Donation\Charge as DonorCharge;
use App\Donor;
use App\RecurringDonation;
use Carbon\Carbon;
use Stripe\Customer;

class Donation
{
    private $donor;

    /**
     * @return \Stripe\Charge
     */
    public function process()
    {
        $this->donor = Donor::findByEmail(request()->json('email'));

        if ($this->isNewDonor()) {
            $stripeCustomer = $this->createCustomerInStripe();
            $this->donor = $this->createDonorInDb($stripeCustomer);
        } else {
            $this->updateStripeCustomer();
        }

        if ($this->isRecurring()) {
            $this->addRecurringDonationToDb();
        }

        $charge = new DonorCharge($this->donor);

        return $charge->handle();
    }

    /**
     * @return bool
     */
    private function isNewDonor(): bool
    {
        return !$this->donor->count();
    }

    /**
     * @return Customer
     */
    private function createCustomerInStripe(): Customer
    {
        $stripeCustomer = new StripeCustomer;

        return $stripeCustomer->create();
    }

    /**
     * @param $stripeCustomer
     * @return mixed
     */
    private function createDonorInDb($stripeCustomer)
    {
        $donor = Donor::create([
            'stripe_id' => $stripeCustomer->id,
            'first_name' => request()->json('first_name'),
            'last_name' => request()->json('last_name'),
            'address' => request()->json('address'),
            'zip' => request()->json('zip'),
            'phone' => request()->json('phone'),
            'email' => request()->json('email'),
        ]);

        return $donor;
    }

    /**
     * Update the stripe customer in case they use a different credit card.
     */
    private function updateStripeCustomer()
    {
        $stripeCustomer = new StripeCustomer;
        $stripeCustomer->updateSource($this->donor, request()->json('token'));
    }

    /**
     * @return bool
     */
    private function isRecurring(): bool
    {
        return request()->json('is_recurring') === true;
    }

    /**
     * We need to add recurring donations to the database so that we can run a cron and charge them each month.
     */
    private function addRecurringDonationToDb()
    {
        RecurringDonation::create([
            'donor_id' => $this->donor->id,
            'amount' => request()->json('amount'),
            'next_donation_on' => Carbon::now()->addMonth()->toDateString(),
            'designation_id' => request()->json('designation'),
            'is_paying_fees' => request()->json('is_paying_fees'),
            'general_comments' => request()->json('general_comments'),
            'mission_support' => request()->json('mission_support'),
            'staff_support' => request()->json('staff_support'),
        ]);
    }
}
