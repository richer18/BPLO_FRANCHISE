<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BploRecordController;
use App\Http\Controllers\TotalRevenueController;
use App\Http\Controllers\TotalRegisteredController;
use App\Http\Controllers\TotalRenewController;
use App\Http\Controllers\TotalExpiryController;
use App\Http\Controllers\TotalExpiredController;

use App\Http\Controllers\TotalSummaryController;

Route::get('/test', function () {
    return response()->json([
        'message' => '✅ Laravel and ReactJS are connected successfully!',
        'status' => 'success'
    ]);
});

Route::get('bplo/registered-mch', [BploRecordController::class, 'registeredMch']);
Route::apiResource('bplo', BploRecordController::class);
Route::get('/bplo', [BploRecordController::class, 'index']);      // All records
Route::get('/bplo/{id}', [BploRecordController::class, 'show']);  // One record
Route::post('/bplo', [BploRecordController::class, 'store']);     // Create
Route::put('/bplo/{id}', [BploRecordController::class, 'update']); // Update
Route::delete('/bplo/{id}', [BploRecordController::class, 'destroy']); // Delete


Route::get('bplo/total-revenue/yearly', [TotalRevenueController::class, 'yearly']);
Route::get('bplo/total-revenue/overall', [TotalRevenueController::class, 'overall']);
Route::get('/total-registered/list', [TotalRegisteredController::class, 'list']); // full details
Route::get('/TotalRegistered', [TotalRegisteredController::class, 'index']);
Route::get('/TotalRenew', [TotalRenewController::class, 'index']);
Route::get('/TotalExpiry', [TotalExpiryController::class, 'index']);
Route::get('/TotalExpired', [TotalExpiredController::class, 'index']);


Route::get('/TotalSummary', [TotalSummaryController::class, 'index']);