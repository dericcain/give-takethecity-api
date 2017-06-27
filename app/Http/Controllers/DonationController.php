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
        \Log::info(print_r(request()->all()));

        try {
            $donation = new Donation;
            $response = $donation->process();
        } catch (\Stripe\Error\Card $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\RateLimit $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\InvalidRequest $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\Authentication $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\ApiConnection $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Stripe\Error\Base $exception) {
            return response()->json(['error' => $exception->getJsonBody()], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        return response()->json($response, 201);
    }

}
