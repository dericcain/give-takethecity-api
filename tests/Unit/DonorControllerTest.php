<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DonorControllerTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function returns_a_donor_by_their_id()
    {
        $this->disableExceptionHandling();

        $donor = factory(\App\Donor::class)->create();

        $response = $this->json('GET', 'api/donors/' . $donor->id);

        $response->assertStatus(200);
        $response->assertJson(['donor' => true]);
    }
}
