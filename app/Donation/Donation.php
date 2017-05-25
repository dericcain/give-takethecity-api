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
        $this->donor = Donor::findByEmailOrPhone(request('email'), request('phone'));

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
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'address' => request('address'),
            'zip' => request('zip'),
            'phone' => request('phone'),
            'email' => request('email'),
        ]);

        return $donor;
    }

    /**
     * Update the stripe customer in case they use a different credit card.
     */
    private function updateStripeCustomer()
    {
        $stripeCustomer = new StripeCustomer;
        $stripeCustomer->updateSource($this->donor, request('token'));
    }

    /**
     * @return bool
     */
    private function isRecurring(): bool
    {
        return request('recurring') === true;
    }

    /**
     * We need to add recurring donations to the database so that we can run a cron and charge them each month.
     */
    private function addRecurringDonationToDb()
    {
        RecurringDonation::create([
            'donor_id' => $this->donor->id,
            'amount' => request('total'),
            'next_donation_on' => Carbon::now()->addMonth()->toDateString(),
            'designation_id' => request('designation'),
            'is_paying_fees' => request('is_covering_fees'),
            'mission_support' => request('mission_support'),
            'staff_support' => request('staff_support'),
        ]);
    }
}
