<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ChargeRecurringDonations;
use App\Donation;
use App\Donor;
use App\RecurringDonation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChargeRecurringDonationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $creditCard;
    protected $request;

    public function setUp()
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
            'amountAsInt' => 2000,
            'amount' => "20.00",
            'recurring' => true,
            'designation_id' => 1,
            'first_name' => 'Deric',
            'last_name' => 'Cain',
            'address' => '123 Main Street',
            'zip' => '35071',
            'phone' => '205 631-2500',
            'email' => 'deric.cain@gmail.com',
            'name_on_card' => 'Deric Cain',
            'token' => $this->getToken(),
            'total' => 2000,
            'is_paying_fees' => true
        ];

        $this->createCustomerInStripe();
    }

    /** @test */
    function recurring_donations_process_all_donations_that_are_due_today()
    {
        $this->disableExceptionHandling();

        $donor = factory(Donor::class)->create([
            'stripe_id' => $this->stripeCustomer->id
        ]);

        factory(RecurringDonation::class, 10)->create([
            'next_donation_on' => Carbon::now()->toDateString(),
            'donor_id' => $donor->id,
            'is_paying_fees' => true
        ]);

        $recurringDonations = new ChargeRecurringDonations;
        $recurringDonations->handle();

        $this->assertCount(10, Donation::all());
    }
}
