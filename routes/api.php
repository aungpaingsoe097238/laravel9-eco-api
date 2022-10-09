<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{
    ApiCardController,
    ApiRoleConroller,
    ApiCityController,
    ApiUserController,
    ApiOrderController,
    ApiPhotoController,
    ApiStateController,
    ApiStockController,
    ApiStatusController,
    ApiCountryController,
    ApiPaymentController,
    ApiProductController,
    ApiProfileController,
    ApiCategoryController,
    ApiOrderPriceController,
    ApiTestController
};


//public routes
Route::prefix('v1')->group(function (){
    Route::post('register',[ApiUserController::class,'register'])->name('api.register');
    Route::post('login',[ApiUserController::class,'login'])->name('api.login');
    Route::get('users/export/', [UsersController::class, 'export']);
});

//auth routes
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function(){
    Route::post('logout',[ApiUserController::class,'logout'])->name('api.logout');
    Route::get('users',[ApiUserController::class,'index']);
    Route::get('users/{id}',[ApiUserController::class,'show']);
    Route::post('assing_role/{id}',[ApiUserController::class,'assignRole']);
    Route::apiResource('stock',ApiStockController::class);
    Route::apiResource('product',ApiProductController::class);
    Route::apiResource('photo',ApiPhotoController::class);
    Route::apiResource('state',ApiStateController::class);
    Route::apiResource('country',ApiCountryController::class);
    Route::apiResource('city',ApiCityController::class);
    Route::apiResource('profile',ApiProfileController::class);
    Route::apiResource('role',ApiRoleConroller::class)->only('index','show');
    Route::apiResource('category',ApiCategoryController::class);
    Route::apiResource('status',ApiStatusController::class);
    Route::apiResource('card',ApiCardController::class);
    Route::apiResource('payment',ApiPaymentController::class);
    Route::apiResource('order_price',ApiOrderPriceController::class);
    Route::apiResource('order',ApiOrderController::class);
    Route::apiResource('test',ApiTestController::class);
});

