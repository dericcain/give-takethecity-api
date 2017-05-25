<?php

namespace Tests\Unit;

use App\Donor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DonorTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    function a_donor_can_be_found_by_their_email_or_phone_number()
    {
        $donor = factory(\App\Donor::class)->create();

        $donorFound = Donor::findByEmailOrPhone($donor->email, $donor->phone);

        $this->assertNotNull($donorFound);
    }
}
