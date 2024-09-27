<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\WorkTimeSummaryController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/employees', [EmployeeController::class, 'store']);
Route::post('/work-times', [WorkTimeController::class, 'store']);
Route::get('/work-times/summary/day', [WorkTimeSummaryController::class, 'summaryByDay']);
Route::get('/work-times/summary/month', [WorkTimeSummaryController::class, 'summaryByMonth']);
