<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/trainings', [App\Http\Controllers\TrainingController::class, 'index']);
    Route::get('/trainings/{id}', [App\Http\Controllers\TrainingController::class, 'show']);
    Route::post('/trainings', [App\Http\Controllers\TrainingController::class, 'store']);
    Route::put('/trainings/{id}', [App\Http\Controllers\TrainingController::class, 'update']);
    Route::delete('/trainings/{id}', [App\Http\Controllers\TrainingController::class, 'destroy']);
    Route::get('/trainings/search/{name}', [App\Http\Controllers\TrainingController::class, 'search']);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); 
