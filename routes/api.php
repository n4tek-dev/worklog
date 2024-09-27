<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/employees', [EmployeeController::class, 'store']);
