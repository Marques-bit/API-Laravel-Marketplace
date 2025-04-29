<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
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
    Route::delete('/orderDelete/{id}', [OrderController::class, 'orderDelete']);
    Route::put('/orderUpdate/{id}', [OrderController::class, 'updateStatus']);
    Route::post('/order', [OrderController::class, 'createOrder']);


    Route::delete('/discountDelete/{id}', [DiscountController::class, 'deleteDiscount']);
    Route::get('/discountAll', [DiscountController::class, 'allDiscounts']);
    Route::get('/discount/{id}', [DiscountController::class, 'getDiscount']);
    Route::put('/discountUpdate/{id}', [DiscountController::class, 'updateDiscount']);
    Route::post('/discountCreate', [DiscountController::class, 'createDiscount']);


    Route::delete('/couponDelete/{id}', [CouponController::class, 'deleteCoupon']);
    Route::get('/couponAll', [CouponController::class, 'allCoupons']);
    Route::get('/coupon/{id}', [CouponController::class, 'getCoupon']);
    Route::put('/couponUpdate/{id}', [CouponController::class, 'updateCoupon']);
    Route::post('/couponCreate', [CouponController::class, 'createCoupon']);


    Route::delete('/cartItemDeleteAll', [CartItemController::class, 'removeAllItemsFromCart']);
    Route::delete('/cartItemDelete/{productId}', [CartItemController::class, 'removeItemFromCart']);
    Route::get('/cartItemGet/{productId}', [CartItemController::class, 'getCartItem']);
    Route::post('/cartItemAdd', [CartItemController::class, 'addItemToCart']);


    Route::delete('/cartDelete', [CartController::class, 'clearCart']);
    Route::get('/cartGet', [CartController::class, 'getCart']);


    Route::delete('/productDelete/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/productAll', [ProductController::class, 'allProducts']);
    Route::get('/product/{id}', [ProductController::class, 'getProduct']);
    Route::put('/productUpdate/{id}', [ProductController::class, 'updateProduct']);
    Route::post('/productCreate', [ProductController::class, 'createProduct']);


    Route::delete('/categoryDelete/{id}', [CategoryController::class, 'deleteCategory']);
    Route::get('/allCategories', [CategoryController::class, 'allCategories']);
    Route::get('/category/{id}', [CategoryController::class, 'getCategory']);
    Route::put('/categoryUpdate/{id}', [CategoryController::class, 'updateCategory']);
    Route::post('/categoryCreate', [CategoryController::class, 'createCategory']);


    Route::delete('/userDelete', [UserController::class, 'deleteUser']);
    Route::delete('/addressDelete/{id}', [AddressController::class, 'addressDelete']);
    Route::get('/addressUser/{id}', [AddressController::class, 'getAddress']);
    Route::put('/userUpdate/{id}', [UserController::class, 'update']);
    Route::put('/updatedAddress/{id}', [AddressController::class, 'addressUpdate']);
    Route::post('/userAddress', [AddressController::class, 'createAddress']);
});

Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'userRegister']);

