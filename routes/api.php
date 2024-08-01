<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BooksController;
use App\Http\Controllers\API\BorrowsController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function(){
    Route::get('me', [AuthController::class, 'getUser']);
    Route::post('profile', [ProfileController::class, 'updateOrCreateProfile']);
    Route::apiResource('role', RolesController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('book', BooksController::class);
    Route::post('borrow', [BorrowsController::class, 'updateOrCreateBorrow']);
    Route::get('borrow', [BorrowsController::class, 'index'] );
    Route::prefix('auth')->group(function(){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);        
        Route::post('logout', [AuthController::class, 'logout']);

    });
});
