<?php

namespace Tests\Unit;

use App\Designation;
use App\Donation;
use App\Donation\DonationReceipt;
use App\Donor;
use App\Mail\DonationReceived;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DonationReceiptTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    function a_donation_receipt_is_sent_to_a_user()
    {
        Mail::fake();

        $donor = factory(Donor::class)->create([
            'email' => '8ef9419efd-14e949@inbox.mailtrap.io'
        ]);
        $donation = factory(Donation::class)->create([
            'donor_id' => $donor->id
        ]);

        $donationReceipt = new DonationReceipt($donation);
        $donationReceipt->send();

        Mail::assertSent(DonationReceived::class, function($mail) use ($donor) {
            return $mail->donation->donor->email == $donor->email;
        });
    }

    /** @test */
    function an_email_with_a_specific_designation_has_a_bcc()
    {
        Mail::fake();

        $designation = factory(Designation::class)->create();
        $donor = factory(Donor::class)->create([
            'email' => '8ef9419efd-14e949@inbox.mailtrap.io'
        ]);
        $donation = factory(Donation::class)->create([
            'donor_id' => $donor->id,
            'designation_id' => $designation->id
        ]);

        $donationReceipt = new DonationReceipt($donation);
        $donationReceipt->send();

        Mail::assertSent(DonationReceived::class, function ($mail) use ($donor, $designation) {
            return $mail->donation->donor->email == $donor->email
                && $mail->hasBcc('andrew@take-the-city.com')
                && $mail->hasBcc($designation->email);
        });
    }

    protected function setUp()
    {
        parent::setUp();
        $this->seed('DesignationSeeder');
    }
}
