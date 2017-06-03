<?php

namespace App\Http\Controllers;

use App\Donation as DBDonation;
use App\Donation\Donation;

class DonationController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'donations' => DBDonation::with('donor', 'designation')->get()
        ]);
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
        } catch (\Stripe\Error\Card $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\RateLimit $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
            // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
            // Something else happened, completely unrelated to Stripe
        }

        return response()->json($response, 201);
    }

}
