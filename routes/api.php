<?php

Route::post('/donations', 'DonationController@store');
Route::get('/designations', 'DesignationController@index');