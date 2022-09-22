<?php

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ApiRoleConroller;
use App\Http\Controllers\V1\ApiCardController;
use App\Http\Controllers\V1\ApiCityController;
use App\Http\Controllers\V1\ApiUserController;
use App\Http\Controllers\V1\ApiPhotoController;
use App\Http\Controllers\V1\ApiStateController;
use App\Http\Controllers\V1\ApiStockController;
use App\Http\Controllers\V1\ApiCountryController;
use App\Http\Controllers\V1\ApiProductController;
use App\Http\Controllers\V1\ApiProfileController;
use App\Http\Controllers\V1\ApiCategoryController;

//public routes
Route::prefix('v1')->group(function (){
    Route::post('register',[ApiUserController::class,'register'])->name('api.register');
    Route::post('login',[ApiUserController::class,'login'])->name('api.login');
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
    Route::apiResource('card',ApiCardController::class);
});
