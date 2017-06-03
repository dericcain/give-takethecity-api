<?php

namespace Tests\Unit;

use App\RecurringDonation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
}
