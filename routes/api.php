<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index']);           // Read all
    Route::post('/', [StudentController::class, 'store']);          // Create
    Route::get('/{student}', [StudentController::class, 'show']);      // Read single
    Route::put('/{student}', [StudentController::class, 'update']);    // Update
    Route::delete('/{student}', [StudentController::class, 'destroy']); // Delete
});