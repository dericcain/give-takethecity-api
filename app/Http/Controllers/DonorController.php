<?php

namespace App\Http\Controllers;

use App\Donor;

class DonorController extends Controller
{
    /**
     * Shows a resources with the given ID.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return response()->json([
            'donor' => Donor::with('donations.designation')->find($id)
        ]);
    }
}
