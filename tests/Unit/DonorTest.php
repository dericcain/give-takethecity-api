<?php

namespace Tests\Unit;

use App\Donor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DonorTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    function a_donor_can_be_found_by_their_email_or_phone_number()
    {
        $this->markTestSkipped();
//        $donor1 = factory(\App\Donor::class)->create([
//            'email' => 'deric@tvstuff.com'
//        ]);
//        $donor2 = factory(\App\Donor::class)->create([
//            'email' => 'deric.cain@gmail.com'
//        ]);
//
//        $donor1Found = Donor::findByEmailOrPhone($donor1->email, null);
//        $donor2Found = Donor::findByEmailOrPhone($donor2->email, $donor2->phone);
//
//        $this->assertEquals($donor1, $donor1Found);
//        $this->assertEquals($donor2, $donor2Found);
    }

    /** @test */
    function a_donor_can_be_found_by_their_email()
    {
        $donor = factory(\App\Donor::class)->create([
            'email' => 'test@test.com'
        ]);

        $donorFound = Donor::findByEmail('test@test.com');

        $this->assertEquals($donor->first_name, $donorFound->first_name);
    }
}
