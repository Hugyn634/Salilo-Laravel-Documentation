<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);           // Read all
        Route::post('/', [StudentController::class, 'store']);          // Create
        Route::get('/{student}', [StudentController::class, 'show']);      // Read single
        Route::put('/{student}', [StudentController::class, 'update']);    // Update
        Route::delete('/{student}', [StudentController::class, 'destroy']); // Delete
    });

    Route::prefix('files')->group(function () {
        Route::get('/', [FileController::class, 'index']);
        Route::post('/', [FileController::class, 'store']);
        Route::get('/{file}', [FileController::class, 'show']);
        Route::get('/{file}/download', [FileController::class, 'download']);
        Route::delete('/{file}', [FileController::class, 'destroy']);
    });
});