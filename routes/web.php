<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentPlanController;
use Illuminate\Auth\Middleware\Authenticate;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return "Cache cleared successfully";
});


//Auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('auth', [AuthController::class, 'login'])->name('login.custom');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

//ban/unban User
Route::put('/users/{id}/ban', [UserController::class, 'ban'])->name('users.update');
Route::put('/users/{id}/hide', [UserController::class, 'hide'])->name('hide');

Route::group(['middleware' => 'islogin'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/block/{id}/{status}', [UserController::class, 'ban']);
    Route::get('/users/hide/{id}/{status}', [UserController::class, 'hide']);
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [AuthController::class, 'show_profile']);
        Route::post('update/password', [AuthController::class, 'update_password']);
    });
});

Route::get('payment_plans', [App\Http\Controllers\PaymentPlanController::class,'index']);
Route::post('payment_plans/store', [App\Http\Controllers\PaymentPlanController::class,'store']);
Route::get('payment_plans/add', [App\Http\Controllers\PaymentPlanController::class,'add']);
Route::get('payment_plans/delete/{id}', [App\Http\Controllers\PaymentPlanController::class,'destroy']);
Route::post('paymentplan/update/{id}', [App\Http\Controllers\PaymentPlanController::class,'update']);
Route::get('payment_plans/edit/{id}', [App\Http\Controllers\PaymentPlanController::class,'edit']);


