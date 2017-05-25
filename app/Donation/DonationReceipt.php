<?php
namespace App\Donation;

use App\Mail\DonationReceived;
use Illuminate\Support\Facades\Mail;

class DonationReceipt
{

    private $donation;

    /**
     * DonationReceipt constructor.
     *
     * @param $donation
     */
    public function __construct($donation)
    {
        $this->donation = $donation;
    }

    /**
     * Send the email to the donor.
     */
    public function send()
    {
        Mail::to($this->donation->donor->email)->send(new DonationReceived($this->donation));
    }
}