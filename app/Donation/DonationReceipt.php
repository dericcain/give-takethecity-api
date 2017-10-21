<?php
namespace App\Donation;

use App\Mail\DonationReceived;
use App\Mail\DonationReceivedAdmin;
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
        // Send the email to the donor
        Mail::to($this->donation->donor->email)
            ->send(new DonationReceived($this->donation));

        // Send the email to the admin team
        Mail::to($this->getUsersToEmail())
            ->send(new DonationReceivedAdmin($this->donation));
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