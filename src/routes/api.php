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
    Route::post('/order', [OrderController::class, 'createOrder']);
    Route::get('/orderAll', [OrderController::class, 'allOrders']);
    Route::put('/orderUpdate/{id}', [OrderController::class, 'updateStatus']);
    Route::delete('/orderDelete/{id}', [OrderController::class, 'orderDelete']);


    Route::post('/discountCreate', [DiscountsController::class, 'createDiscount']);
    Route::get('/discountAll', [DiscountsController::class, 'allDiscounts']);
    Route::put('/discountUpdate/{id}', [DiscountsController::class, 'updateDiscount']);
    Route::delete('/discountDelete/{id}', [DiscountsController::class, 'deleteDiscount']);
    Route::get('/discount/{id}', [DiscountsController::class, 'getDiscount']);


    Route::post('/couponCreate', [CouponsController::class, 'createCoupon']);
    Route::get('/couponAll', [CouponsController::class, 'allCoupons']);
    Route::put('/couponUpdate/{id}', [CouponsController::class, 'updateCoupon']);
    Route::delete('/couponDelete/{id}', [CouponsController::class, 'deleteCoupon']);
    Route::get('/coupon/{id}', [CouponsController::class, 'getCoupon']);


    Route::post('/orderCreate', [OrderController::class, 'createOrder']);
    Route::delete('/orderDelete/{id}', [OrderController::class, 'orderDelete']);
    Route::put('/orderUpdate/{id}', [OrderController::class, 'updateStatus']);


    Route::post('/cartItemAdd', [CartItemController::class, 'addItemToCart']);
    Route::delete('/cartItemDelete/{productId}', [CartItemController::class, 'removeItemFromCart']);
    Route::delete('/cartItemDeleteAll', [CartItemController::class, 'removeAllItemsFromCart']);
    Route::get('/cartItemGet/{productId}', [CartItemController::class, 'getCartItem']);

    Route::get('/cartUser', [CartController::class, 'getUserCart']);
    Route::get('/cartGet', [CartController::class, 'getCart']);
    Route::delete('/cartDelete', [CartController::class, 'clearCart']);


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

