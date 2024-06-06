<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\userController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//login
Route::middleware(['throttle:login'])->group(function () {          
    Route::post('admin/login',[userController::class,'adminLogin']);
    Route::post('customer/login',[userController::class,'customerLogin']);
    });
    
    
    
    //register
    Route::middleware(['throttle:login'])->group(function () {          
        Route::post('customer/registration',[userController::class,'customerRegistration']);
        Route::post('admin/registration',[userController::class,'adminRegistration']);
        });
    
    
    