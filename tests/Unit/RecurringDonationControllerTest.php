<?php

namespace Tests\Unit;

use App\RecurringDonation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RecurringDonationControllerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        factory(RecurringDonation::class, 100)->create();
    }

    /** @test */
    function returns_all_recurring_donations()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', '/api/recurring-donations');

        $response->assertStatus(200);
        $response->assertJson(['donations' => true]);
    }

    /** @test */
    function a_next_payment_date_can_be_updated()
    {
        $this->disableExceptionHandling();
        $recurringDonation = RecurringDonation::first();

        $nextDonationDate = Carbon::now()->addDays(15)->toDateString();

        $response = $this->json('POST', '/api/recurring-donations/' . $recurringDonation->id, [
            'next_donation_on' => $nextDonationDate
        ]);


        $response->assertStatus(200);
        $this->assertEquals(RecurringDonation::find($recurringDonation->id)->next_donation_on->toDateString(),
            $nextDonationDate);
    }

    /** @test */
    function a_donation_can_be_marked_as_inactive()
    {
        $this->disableExceptionHandling();
        $recurringDonation = RecurringDonation::first();

        $response = $this->json('POST', '/api/recurring-donations/' . $recurringDonation->id, [
            'is_active' => false
        ]);

        $response->assertStatus(200);
        $this->assertFalse(RecurringDonation::find($recurringDonation->id)->is_active);
    }
}
