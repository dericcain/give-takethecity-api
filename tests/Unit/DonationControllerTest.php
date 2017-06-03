<?php

namespace Tests\Unit;

use App\Donation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DonationControllerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        factory(Donation::class, 100)->create();
    }

    /** @test */
    function a_user_can_retrieve_a_list_of_donations()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', 'api/donations');

        $response->assertStatus(200);
        $response->assertJson(['donations' => true]);
    }
}
