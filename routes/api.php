<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Api\adminController;
use App\Http\Controllers\Api\managerController;
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

    Route::post('login',[userController::class,'userLogin']);
    });
    

//admin

Route::middleware(['auth:api','scopes:admin','throttle:user'])->group(function () {  
    Route::post('admin/addVendor',[adminController::class,'addVendor']);
    Route::post('admin/addStock',[adminController::class,'addStock']);
    Route::post('admin/addRecipe',[adminController::class,'addRecipe']);
    Route::post('admin/addRecipe-Process',[adminController::class,'recipeProcess']);
    Route::post('admin/viewRecipe',[adminController::class,'viewRecipe']);
    Route::get('admin/viewStock',[adminController::class,'viewStock']);
    Route::get('admin/viewOrder',[adminController::class,'viewOrder']);
    Route::post('admin/viewReport',[adminController::class,'viewReport']);
   
    });
    



//manager
    
Route::middleware(['auth:api','throttle:user','scopes:manager'])->group(function () {  
    Route::post('manager/addOrder',[managerController::class,'addOrder']);
    Route::post('manager/OutwardQuantity',[managerController::class,'addOutward']);
    
    });

    