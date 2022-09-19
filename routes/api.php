<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ApiProfileController;
use App\Http\Controllers\V1\ApiCityController;
use App\Http\Controllers\V1\ApiCountryController;
use App\Http\Controllers\V1\ApiPhotoController;
use App\Http\Controllers\V1\ApiProductController;
use App\Http\Controllers\V1\ApiStateController;
use App\Http\Controllers\V1\ApiStockController;
use App\Http\Controllers\V1\ApiUserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public routes
Route::prefix('v1')->group(function (){
    Route::post('register',[ApiUserController::class,'register'])->name('api.register');
    Route::post('login',[ApiUserController::class,'login'])->name('api.login');
});

//auth routes
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function(){
    Route::post('logout',[ApiUserController::class,'logout'])->name('api.logout');
    Route::apiResource('stock',ApiStockController::class)->middleware('isAdmin');
    Route::apiResource('product',ApiProductController::class)->middleware('isAdmin');
    Route::apiResource('photo',ApiPhotoController::class)->middleware('isAdmin');
    Route::apiResource('state',ApiStateController::class);
    Route::apiResource('country',ApiCountryController::class);
    Route::apiResource('city',ApiCityController::class);
    Route::apiResource('customer',ApiCustomerController::class);
});

