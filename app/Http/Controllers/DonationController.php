<?php

namespace App\Http\Controllers;

use App\Donation\Donation;
use App\Http\Requests\DonationRequest;

class DonationController extends Controller
{

    /**
     * Display the home page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('donations.create');
    }

    /**
     * Process a donation and then store it in the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        try {
            $donation = new Donation;
            $response = $donation->process();
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        return response()->json($response, 201);
    }

}
