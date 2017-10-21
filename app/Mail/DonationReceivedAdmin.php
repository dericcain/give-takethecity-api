<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class DonationReceivedAdmin extends Mailable
{

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
        return $this->view('emails.donations.received_admin')
                    ->with([
                        'donation' => $this->donation
                    ]);
    }
}
