<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\SwiftController;
use App\Http\Controllers\BudgetHoldersController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function (){


    // Swift
    Route::resource('swift', SwiftController::class);
    Route::post('/swift/import', [SwiftController::class, 'import']);
    Route::post('/swift/export', [SwiftController::class, 'export']);


    // Бюджетополучатели
    Route::resource('budget-holders', BudgetHoldersController::class);
    Route::post('/budget-holders/import', [BudgetHoldersController::class, 'import']);
    Route::post('/budget-holders/export', [BudgetHoldersController::class, 'export']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
