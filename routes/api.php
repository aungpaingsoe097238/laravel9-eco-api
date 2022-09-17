<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ApiCustomerController;
use App\Http\Controllers\V1\ApiCityController;
use App\Http\Controllers\V1\ApiCountryController;
use App\Http\Controllers\V1\ApiImageController;
use App\Http\Controllers\V1\ApiProductController;
use App\Http\Controllers\V1\ApiStateController;
use App\Http\Controllers\V1\ApiStockController;
use App\Http\Controllers\V1\ApiUserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){
    Route::post('register',[ApiUserController::class,'register'])->name('api.register');
    Route::post('login',[ApiUserController::class,'login'])->name('api.login');
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function(){
    Route::post('logout',[ApiUserController::class,'logout'])->name('api.logout');
});
