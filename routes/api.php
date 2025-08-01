<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Employee\VacationController;
use App\Http\Controllers\Api\Employee\ResignationController;
use App\Http\Controllers\Api\Employee\HomeController;
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
Route::post('auth/login',[AuthController::class,'login']);
Route::group(['middleware'=>'auth:api'],function(){
   Route::post('auth/logout',[AuthController::class,'logout']);
   Route::get('auth/user',[AuthController::class,'user']); 

   Route::group(['prefix'=>'vacations'],function(){
    Route::get('/',[VacationController::class,'index']);
    Route::post('/',[VacationController::class,'store']);
    Route::get('/request',[VacationController::class,'vacationRequests']);
    Route::post('{id}/delete',[VacationController::class,'destroy']);
   });
   Route::group(['prefix'=>'resignations'],function(){
    Route::get('/',[ResignationController::class,'index']);
    Route::post('/',[ResignationController::class,'store']);
    Route::post('{id}/delete',[ResignationController::class,'destroy']);
   });
   Route::group(['prefix'=>'home'],function(){
      Route::get('/salaries',[HomeController::class,'salary']);
      Route::get('/news',[HomeController::class,'news']);
   });
});
