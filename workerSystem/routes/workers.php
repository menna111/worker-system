<?php

use App\Http\Controllers\API\WorkerAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(WorkerAuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
