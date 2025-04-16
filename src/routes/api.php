<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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
    Route::delete('/productDelete/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/productAll', [ProductController::class, 'allProducts']);
    Route::get('/product/{id}', [ProductController::class, 'getProduct']);
    Route::put('/productUpdate/{id}', [ProductController::class, 'updateProduct']);
    Route::post('/productCreate', [ProductController::class, 'createProduct']);
    Route::delete('/categoryDelete/{id}', [CategoryController::class, 'deleteCategory']);
    Route::get('/allCategories', [CategoryController::class, 'allCategories']);
    Route::get('/category/{id}', [CategoryController::class, 'getCategory']);
    Route::put('/categoryUpdate/{id}', [CategoryController::class, 'updateCategory']);
    Route::post('categoryCreate', [CategoryController::class, 'createCategory']);
    Route::delete('/userDelete', [UserController::class, 'deleteUser']);
    Route::delete('/addressDelete/{id}', [AddressController::class, 'addressDelete']);
    Route::put('/userUpdate/{id}', [UserController::class, 'update']);
    Route::put('/updatedAddress/{id}', [AddressController::class, 'addressUpdate']);
    Route::post('/userAddress', [AddressController::class, 'createAddress']);
});

Route::get('/addressUser/{id}', [AddressController::class, 'getAddress']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'register']);

