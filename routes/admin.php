<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DayController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SliderController;
use App\Http\Controllers\Dashboard\SubCategoryController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WorkerController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::get('/test', function () {
    Auth::guard('admin')->logout();
    return view('dashboard.auth.login');
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {


    Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
        Route::get('/', [AuthController::class, 'showForm'])->name('admin.showForm');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        /////////////////////////////////////// Role Management //////////////////////////////
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.roles.store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.roles.edit');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
            Route::post('/destroy', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
        });

        /////////////////////////////////////// Admin Management //////////////////////////////

        Route::get('/all', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
        Route::post('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::post('/destroy', [AdminController::class, 'destroy'])->name('admin.destroy');

        /////////////////////////////////////// City Management //////////////////////////////
        Route::group(['prefix' => 'cities'], function () {
            Route::get('/', [CityController::class, 'index'])->name('admin.cities.index');
            Route::get('/create', [CityController::class, 'create'])->name('admin.cities.create');
            Route::post('/store', [CityController::class, 'store'])->name('admin.cities.store');
            Route::get('/edit/{id}', [CityController::class, 'edit'])->name('admin.cities.edit');
            Route::post('/update/{id}', [CityController::class, 'update'])->name('admin.cities.update');
            Route::post('/destroy', [CityController::class, 'destroy'])->name('admin.cities.destroy');
        });

        /////////////////////////////////////// Days Management //////////////////////////////
        Route::group(['prefix' => 'days'], function () {
            Route::get('/', [DayController::class, 'index'])->name('admin.days.index');
            Route::get('/create', [DayController::class, 'create'])->name('admin.days.create');
            Route::post('/store', [DayController::class, 'store'])->name('admin.days.store');
            Route::get('/edit/{id}', [DayController::class, 'edit'])->name('admin.days.edit');
            Route::post('/update/{id}', [DayController::class, 'update'])->name('admin.days.update');
            Route::post('/destroy', [DayController::class, 'destroy'])->name('admin.days.destroy');
        });

        /////////////////////////////////////// Categories Management //////////////////////////////
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::post('/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
            Route::post('/destroy', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        });


        /////////////////////////////////////// SubCategories Management //////////////////////////////
        Route::group(['prefix' => 'sub-categories'], function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('admin.sub.categories.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('admin.sub.categories.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('admin.sub.categories.store');
            Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('admin.sub.categories.edit');
            Route::post('/update/{id}', [SubCategoryController::class, 'update'])->name('admin.sub.categories.update');
            Route::post('/destroy', [SubCategoryController::class, 'destroy'])->name('admin.sub.categories.destroy');
        });

        /////////////////////////////////////// Users Management //////////////////////////////
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
            Route::post('/destroy', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        /////////////////////////////////////// Worker Management //////////////////////////////
        Route::group(['prefix' => 'workers'], function () {
            Route::get('/', [WorkerController::class, 'index'])->name('admin.workers.index');
            Route::get('/create', [WorkerController::class, 'create'])->name('admin.workers.create');
            Route::post('/store', [WorkerController::class, 'store'])->name('admin.workers.store');
            Route::get('/edit/{id}', [WorkerController::class, 'edit'])->name('admin.workers.edit');
            Route::post('/update/{id}', [WorkerController::class, 'update'])->name('admin.workers.update');
            Route::post('/destroy', [WorkerController::class, 'destroy'])->name('admin.workers.destroy');
        });

        /////////////////////////////////////// Slider Management //////////////////////////////
        Route::group(['prefix' => 'sliders'], function () {
            Route::get('/', [SliderController::class, 'index'])->name('admin.sliders.index');
            Route::get('/create', [SliderController::class, 'create'])->name('admin.sliders.create');
            Route::post('/store', [SliderController::class, 'store'])->name('admin.sliders.store');
            Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('admin.sliders.edit');
            Route::post('/update/{id}', [SliderController::class, 'update'])->name('admin.sliders.update');
            Route::post('/destroy', [SliderController::class, 'destroy'])->name('admin.sliders.destroy');
        });

    });
});


