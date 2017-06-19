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
        Mail::to($this->donation->donor->email)
            ->bcc($this->getUsersToEmail())
            ->send(new DonationReceived($this->donation));
    }

    /**
     * Sometimes we need to email other users because the donation is for them.
     *
     * @return array|string
     */
    public function getUsersToEmail()
    {
        if (!is_null($this->donation->designation->email)) {
            return [
                $this->donation->designation->email,
                'andrew@take-the-city.com'
            ];
        }

        return 'andrew@take-the-city.com';
    }
}