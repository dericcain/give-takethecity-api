<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Stripe\Customer;
use Stripe\Token;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $creditCard;
    protected $request;
    protected $stripeCustomer;

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
            }

            public function report(\Exception $e)
            {
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function createCustomerInStripe()
    {
        $this->stripeCustomer = Customer::create([
            'description' => 'John, Doe',
            'email' => 'john@email.com',
            'source' => $this->getToken(),
            'metadata' => [
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'address' => request('address'),
                'zip' => request('zip'),
                'phone' => request('phone'),
            ]
        ], ['api_key' => config('services.stripe.secret')]);
    }

    protected function getToken()
    {
        return Token::create($this->creditCard, ['api_key' => config('services.stripe.key')])->id;
    }
}
