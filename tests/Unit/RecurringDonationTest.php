<?php

namespace Tests\Unit;

use App\RecurringDonation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RecurringDonationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    function query_gets_all_recurring_donations_for_today()
    {
        factory(RecurringDonation::class, 10)->create([
            'next_donation_on' => Carbon::now()->toDateString()
        ]);

        factory(RecurringDonation::class, 5)->create([
            'next_donation_on' => Carbon::now()->addMonth()->toDateString()
        ]);

        $todaysRecurringDonations = RecurringDonation::needChargingToday();

        $this->assertCount(10, $todaysRecurringDonations);
    }

    /** @test */
    function a_month_is_added_to_a_recurring_donation_when_the_donation_is_made()
    {
        $recurringDonation = factory(RecurringDonation::class)->create([
            'next_donation_on' => Carbon::now()->toDateString()
        ]);

        $recurringDonation->updateNextDonationOn();

        $this->assertEquals(Carbon::now()->addMonth()->toDateString(),
            $recurringDonation->next_donation_on->toDateString());
    }
}
