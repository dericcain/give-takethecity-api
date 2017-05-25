<?php

namespace Tests\Feature;

use App\Donation\FakePaymentGateway;
use App\Donor;
use App\RecurringDonation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Stripe\Customer;
use Tests\TestCase;

class MakeDonationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, WithoutMiddleware;

    protected $request;

    protected $creditCard;

    /** @test */
    function a_person_can_make_a_donation()
    {
        $this->disableExceptionHandling();

        $request = $this->postDonationRequest();

        $request->assertStatus(201);
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function postDonationRequest()
    {
        return $this->post('/api/donations', $this->request);
    }

    /** @test */
    function if_a_donation_is_recurring_it_is_inserted_into_the_database()
    {
        $this->request['recurring'] = true;

        $response = $this->postDonationRequest();
        $recurringDonation = RecurringDonation::whereAmount($this->request['total'])->first();

        $response->assertStatus(201);
        $this->assertNotNull(RecurringDonation::all());

        $this->assertEquals($this->request['designation'], $recurringDonation->designation->id);
    }

    /** @test */
    function if_a_donor_is_not_in_the_database_the_donor_is_created()
    {
        $this->postDonationRequest();

        $donor = Donor::findByEmailOrPhone($this->request['email'], $this->request['phone']);

        $this->assertEquals(1, $donor->count());
    }

    /** @test */
    function if_a_donor_uses_a_different_card_it_is_updated_in_stripe()
    {
        $this->postDonationRequest();
        $donorStripeId = Donor::whereEmail($this->request['email'])->first()->stripe_id;
        $customerLast4 = Customer::retrieve($donorStripeId,
            ['api_key' => config('services.stripe.secret')])['sources']['data'][0]['last4'];

        $this->creditCard['card']['number'] = '5555555555554444';
        $this->request['token'] = $this->getToken();
        $this->postDonationRequest();
        $customerUpdateCardLast4 = Customer::retrieve($donorStripeId,
            ['api_key' => config('services.stripe.secret')])['sources']['data'][0]['last4'];

        $this->assertEquals('4242', $customerLast4);
        $this->assertEquals('4444', $customerUpdateCardLast4);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->seed('DesignationSeeder');

        $this->creditCard = [
            'card' => [
                'number' => '4242424242424242',
                'cvc' => '123',
                'exp_month' => '1',
                'exp_year' => date('Y') + 1,
            ]
        ];

        $this->request = [
            'amount' => 2000,
            'recurring' => false,
            'designation' => 1,
            'first_name' => 'Deric',
            'last_name' => 'Cain',
            'address' => '123 Main Street',
            'zip' => '35071',
            'phone' => '205 631-2500',
            'email' => 'deric.cain@gmail.com',
            'name_on_card' => 'Deric Cain',
            'token' => $this->getToken(),
            'total' => 2000,
            'is_covering_fees' => false
        ];
    }
}
