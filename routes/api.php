<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkTimeController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/employees', [EmployeeController::class, 'store']);
Route::post('/work-times', [WorkTimeController::class, 'store']);
