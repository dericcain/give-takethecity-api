<?php

namespace Tests\Unit;

use App\Donation\StripeCustomer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class StripeCustomerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, WithoutMiddleware;

    protected $request;

    protected $creditCard;

    protected function setUp()
    {
        parent::setUp();

        $this->creditCard = [
            'card' => [
                'number' => '4242424242424242',
                'cvc' => '123',
                'exp_month' => '1',
                'exp_year' => date('Y') + 1,
            ]
        ];

        $this->request = [
            'description' => 'John, Doe',
            'email' => 'john@email.com',
            'source' => $this->getToken(),
            'metadata' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main Street',
                'zip' => '35209',
                'phone' => '2059021234',
            ]
        ];

        $this->createCustomerInStripe();
    }

    /** @test */
    function a_customer_is_created_in_stripe()
    {
        $customer = new StripeCustomer;
        $customer->create();
    }

    /**  */
    function a_customers_information_is_updated_in_stripe()
    {

    }
}
