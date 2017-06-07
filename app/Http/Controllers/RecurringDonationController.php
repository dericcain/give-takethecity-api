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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        RecurringDonation::find($id)->update(request()->json()->all());

        return response()->json([
            'success' => true
        ], 200);
    }
}
