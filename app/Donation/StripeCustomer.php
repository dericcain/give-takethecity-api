<?php

namespace App\Donation;

use Stripe\Customer;

class StripeCustomer
{
    /**
     * @var Customer
     */
    private $stripeCustomer;

    /**
     * @return Customer
     */
    public function create()
    {
        return Customer::create([
            'description' => request('last_name') . ', ' . request('first_name'),
            'email' => request('email'),
            'source' => request('token'),
            'metadata' => [
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'address' => request('address'),
                'zip' => request('zip'),
                'phone' => request('phone'),
            ]
        ], ['api_key' => config('services.stripe.secret')]);
    }

    /**
     * @param $donor
     * @param $token
     */
    public function updateSource($donor, $token)
    {
        $this->stripeCustomer = $this->getCustomerFromStripe($donor);
        $this->stripeCustomer->source = $token;
        $this->stripeCustomer->save();
    }

    /**
     * @param $donor
     * @return Customer
     */
    private function getCustomerFromStripe($donor)
    {
        return Customer::retrieve($donor->stripe_id, ['api_key' => config('services.stripe.secret')]);
    }
}