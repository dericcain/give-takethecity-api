<?php

namespace App\Http\Controllers;

use App\Designation;

class DesignationController extends Controller
{
    /**
     * Shows a list of resources.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return response()->json(Designation::all(), 200);
    }
}
