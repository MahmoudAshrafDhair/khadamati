<?php

use App\Http\Controllers\API\User\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'guest:user-api','prefix' => 'user'],function (){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/check-code',[AuthController::class,'check_code']);
    Route::post('/login',[AuthController::class,'login']);
});

Route::group(['middleware' => 'guest:worker-api','prefix' => 'worker'],function (){
    Route::post('/register',[\App\Http\Controllers\API\Worker\AuthController::class,'register']);
    Route::post('/check-code',[\App\Http\Controllers\API\Worker\AuthController::class,'check_code']);
    Route::post('/login',[\App\Http\Controllers\API\Worker\AuthController::class,'login']);
});

Route::group(['middleware' => 'auth:user-api','prefix' => 'user'],function (){
    Route::post('/logout',[AuthController::class,'logout']);
});

Route::group(['middleware' => 'auth:worker-api','prefix' => 'worker'],function (){
    Route::post('/logout',[\App\Http\Controllers\API\Worker\AuthController::class,'logout']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
