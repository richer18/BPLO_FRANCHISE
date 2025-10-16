<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BploRecordController;

Route::get('/test', function () {
    return response()->json([
        'message' => '✅ Laravel and ReactJS are connected successfully!',
        'status' => 'success'
    ]);
});

Route::apiResource('bplo', BploRecordController::class);
Route::get('/bplo', [BploRecordController::class, 'index']);      // All records
Route::get('/bplo/{id}', [BploRecordController::class, 'show']);  // One record
Route::post('/bplo', [BploRecordController::class, 'store']);     // Create
Route::put('/bplo/{id}', [BploRecordController::class, 'update']); // Update
Route::delete('/bplo/{id}', [BploRecordController::class, 'destroy']); // Delete
