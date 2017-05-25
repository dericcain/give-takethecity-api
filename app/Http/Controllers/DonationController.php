<?php

namespace App\Http\Controllers;

use App\Donation\Donation;
use App\Helpers\Phone;
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
     * @param DonationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DonationRequest $request)
    {
        request()->merge([
            'phone' => Phone::onlyNumbers(request('phone')),
        ]);

        try {
            $donation = new Donation;
            $response = $donation->process();
        } catch (\Exception $exception) {
            dd($exception->getMessage(), request()->all());
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        return response()->json($response, 201);
    }
}
