<?php

use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\User\AuthController;
use App\Http\Controllers\API\User\FavoriteController;
use App\Http\Controllers\API\User\ForgetPasswordController;
use App\Http\Controllers\API\User\OrderController;
use App\Http\Controllers\API\User\ProfileController;
use App\Http\Controllers\API\Worker\WorkerForgetPasswordController;
use App\Http\Controllers\API\Worker\WorkerOrderController;
use App\Http\Controllers\API\Worker\WorkerProfileController;
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

Route::get('/cities',[GeneralController::class,'getCities']);
Route::get('/subCategories',[GeneralController::class,'getServices']);
/////////////////////////// Setting /////////////////////////////////////////
Route::get('/pages',[GeneralController::class,'getPages']);
/////////////////////////// Contact Us /////////////////////////////////////////
Route::post('/contact-us',[GeneralController::class,'contactUs']);

Route::get('/show',[AuthController::class,'showMessage'])->name('login');



Route::group(['middleware' => 'guest:user-api','prefix' => 'user'],function (){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/check-code',[AuthController::class,'check_code']);
    Route::post('/login',[AuthController::class,'login']);
    /////////////////////////// Forget Password /////////////////////////////////////////
    Route::post('/forget-password-check-email',[ForgetPasswordController::class,'checkEmail']);
    Route::post('/forget-password-check-code',[ForgetPasswordController::class,'checkCode']);
    Route::post('/forget-password-change-password',[ForgetPasswordController::class,'changePassword']);
});

Route::group(['middleware' => 'guest:worker-api','prefix' => 'worker'],function (){
    Route::post('/register',[\App\Http\Controllers\API\Worker\AuthController::class,'register']);
    Route::post('/check-code',[\App\Http\Controllers\API\Worker\AuthController::class,'check_code']);
    Route::post('/login',[\App\Http\Controllers\API\Worker\AuthController::class,'login']);
    /////////////////////////// Forget Password /////////////////////////////////////////
    Route::post('/forget-password-check-email',[WorkerForgetPasswordController::class,'checkEmail']);
    Route::post('/forget-password-check-code',[WorkerForgetPasswordController::class,'checkCode']);
    Route::post('/forget-password-change-password',[WorkerForgetPasswordController::class,'changePassword']);
});

Route::group(['middleware' => 'auth:user-api','prefix' => 'user'],function (){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/subCategories-by-categories-id',[OrderController::class,'getSubCategoriesByCategoriesId']);
    Route::get('/get-categories',[OrderController::class,'getCategories']);
    Route::get('/home',[GeneralController::class,'home']);
    /////////////////////////// Favorites /////////////////////////////////////////
    Route::post('/add-favorite',[FavoriteController::class,'addToFavorite']);
    Route::get('/get-favorites',[FavoriteController::class,'getFavorites']);
    Route::post('/delete-favorite',[FavoriteController::class,'deleteFavorites']);
    /////////////////////////// Order /////////////////////////////////////////
    Route::get('/get_workers',[OrderController::class,'getWorkers']);
    Route::post('/store-order',[OrderController::class,'storeOrder']);
    Route::post('/get-orders',[OrderController::class,'getOrders']);
    Route::post('/change-type-to-completed',[OrderController::class,'ChangeTypeToCompleted']);
    /////////////////////////// Rating /////////////////////////////////////////
    Route::post('/store-Rating',[OrderController::class,'storeRating']);
    /////////////////////////// Profile /////////////////////////////////////////
    Route::get('/get_profile',[ProfileController::class,'getProfile']);
    Route::post('/update_profile',[ProfileController::class,'updateProfile']);
    Route::post('/update_password',[ProfileController::class,'updatePassword']);
});

Route::group(['middleware' => 'auth:worker-api','prefix' => 'worker'],function (){
    Route::post('/logout',[\App\Http\Controllers\API\Worker\AuthController::class,'logout']);
    /////////////////////////// Order /////////////////////////////////////////
    Route::post('/get-orders',[WorkerOrderController::class,'getOrders']);
    Route::post('/accept-order',[WorkerOrderController::class,'acceptOrder']);
    Route::post('/rejected-order',[WorkerOrderController::class,'rejectedOrder']);
    Route::post('/completed-order',[WorkerOrderController::class,'completedOrder']);
    /////////////////////////// Appointments /////////////////////////////////////////
    Route::get('/get-days',[WorkerProfileController::class,'getDays']);
    Route::post('/add-appointment',[WorkerProfileController::class,'addAppointments']);
    Route::post('/update-appointment',[WorkerProfileController::class,'updateAppointments']);
    Route::get('/get-appointments',[WorkerProfileController::class,'getAppointments']);
    Route::get('/get-profile',[WorkerProfileController::class,'getProfile']);
    Route::post('/update-profile',[WorkerProfileController::class,'updateProfile']);
    Route::post('/update-password',[WorkerProfileController::class,'updatePassword']);
});

