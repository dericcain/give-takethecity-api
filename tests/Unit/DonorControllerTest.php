<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

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

    /** @test */
    function gets_a_list_of_all_donors()
    {
        factory(\App\Donor::class, 20)->create();

        $response = $this->json('GET', 'api/donors/');

        $response->assertStatus(200);
        $response->assertJson(['donors' => true]);
        $this->assertCount(20, $response->json()['donors']);
    }
}
