<?php

namespace App\Http\Controllers;

use App\RecurringDonation;

class RecurringDonationController extends Controller
{
    /**
     * Shows a list of resources.
     */
    public function index()
    {
        return response()->json([
            'donations' => RecurringDonation::with('donor', 'designation')->get()
        ]);
    }
}
