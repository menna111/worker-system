<?php

use App\Http\Controllers\API\ClientAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(ClientAuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
