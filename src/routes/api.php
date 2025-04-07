<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
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
Route::middleware('auth:sanctum')->group( function () {
    Route::delete('/userDelete', [UserController::class, 'deleteUser']);
    Route::put('/userUpdate/{id}', [UserController::class, 'update']);
    Route::put('/addressUpdate', [AddressController::class, 'addressUpdate']);
});

Route::post('/userAddress', [AddressController::class, 'createAddress']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'register']);

