<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SuccessfulEmailController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/logout', [AuthenticationController::class, 'logout']);


Route::middleware('authentication')->group(function () {
    Route::get('/emails', [SuccessfulEmailController::class, 'index']);
    Route::post('/emails', [SuccessfulEmailController::class, 'store']);
    Route::get('/emails/{email}', [SuccessfulEmailController::class, 'show']);
    Route::put('/emails/{email}', [SuccessfulEmailController::class, 'update']);
    Route::delete('/emails/{email}', [SuccessfulEmailController::class, 'destroy']);
});
