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
        return $this->markdown('emails.donations.received', [
            'donationMessage' => $this->constructDonationMessage()
        ]);
    }

    /**
     * @return string
     */
    private function constructDonationMessage()
    {
        if ($this->donation->is_recurring) {
            return 'You gave a recurring donation of <strong>$' . number_format($this->donation->amount / 100,
                    2) . '</strong> and you designated your gift to <strong>' . $this->donation->designation->name . '.</strong>';
        }

        return 'You gave a one-time donation of <strong>$' . number_format($this->donation->amount / 100,
                2) . '</strong> and you designated your gift to <strong>' . $this->donation->designation->name . '</strong>.';
    }


}
