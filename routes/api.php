<?php

Route::post('/donations', 'DonationController@store');
Route::get('/designations', 'DesignationController@index');

Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::get('/donors/{id}', 'DonorController@show');
    Route::get('/donations', 'DonationController@index');
    Route::get('/recurring-donations', 'RecurringDonationController@index');
});
