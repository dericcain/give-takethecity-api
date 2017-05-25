<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class DonationReceived extends Mailable
{
    /**
     * @var Donor
     */
    public $donation;

    /**
     * Create a new message instance.
     *
     * @param $donation
     */
    public function __construct($donation)
    {
        $this->donation = $donation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.donations.received');
    }
}
