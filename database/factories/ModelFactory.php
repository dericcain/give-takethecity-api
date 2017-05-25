<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Donor::class, function (Faker\Generator $faker) {

    return [
        'stripe_id' => 'random_stripe_id',
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address' => $faker->streetAddress,
        'zip' => $faker->numberBetween(11111, 99999),
        'phone' => '2055551234',
        'email' => $faker->safeEmail,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\RecurringDonation::class, function (Faker\Generator $faker) {

    return [
        'donor_id' => 1,
        'amount' => $faker->numberBetween(1000, 100000),
        'next_donation_on' => $faker->dateTimeBetween('today', '+5 days'),
        'designation_id' => $faker->numberBetween(1, 5),
        'is_paying_fees' => false
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Donation::class, function (Faker\Generator $faker) {

    return [
        'donor_id' => 1,
        'amount' => $faker->numberBetween(1000, 100000),
        'is_recurring' => false,
        'designation_id' => $faker->numberBetween(1, 5),
        'is_paying_fees' => false
    ];
});
