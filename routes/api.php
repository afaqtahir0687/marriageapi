<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmailVerificationController;
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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/social', [AuthController::class, 'loginUserWithSocial']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('/verify-email-code', [UserController::class, 'verifyEmailCode']);
Route::middleware('auth:sanctum')->group(function () {

    // Your API routes
    Route::post('/auth/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/updateProfileImage', [AuthController::class, 'updateProfileImage']);
    Route::post('/auth/showProfileImage', [AuthController::class, 'showProfileImage']);
    Route::post('/auth/hideProfile', [AuthController::class, 'hideProfile']);
    Route::post('/auth/userQuestions', [AuthController::class, 'userQuestions']);
    Route::post('/auth/userDelete', [AuthController::class, 'userDelete']);
    Route::post('/auth/updateLatitudeLongitude', [UserController::class, 'updateLatitudeLongitude']);
    Route::get('/auth/getLatitudeLongitude', [UserController::class, 'getLatitudeLongitude']);
    Route::group(['prefix' => 'users'], function () {

        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/detail', [UserController::class, 'getUserDetail']);
        Route::get('/{id}/percentage', [UserController::class, 'getUserPecentage']);
        Route::get('/{id}/online', [UserController::class, 'userOnline']);

        Route::get('/nerebyUsersList', [UserController::class, 'nerebyUsersList']);
        Route::post('/filterUserList', [UserController::class, 'filterUserList']);

        Route::post('/add-social-accounts', [UserController::class, 'addSocialAccounts']);
        Route::get('/get-social-accounts', [UserController::class, 'getSocialAccounts']);

        Route::post('/profileLikeAndDislike', [UserController::class, 'profileLikeAndDislike']);
        Route::post('/profileWithLikeAndDislike', [UserController::class, 'profileWithLikeAndDislike']);
        Route::get('/allLikedUsers', [UserController::class, 'allLikedUsers']);

        Route::group(['prefix' => 'block'], function () {
            Route::get('get', [App\Http\Controllers\Api\BlockUserController::class, 'getAllBlocked']);
            Route::post('add', [App\Http\Controllers\Api\BlockUserController::class, 'markBlock']);
            Route::post('remove', [App\Http\Controllers\Api\BlockUserController::class, 'removeBlock']);
        });
    });
    Route::group(['prefix' => 'chat'], function () {
        Route::post('get', [App\Http\Controllers\Api\ChatController::class, 'getAllChats']);
        Route::get('get/{id}', [App\Http\Controllers\Api\ChatController::class, 'getChat']);
        Route::post('create/text', [App\Http\Controllers\Api\ChatController::class, 'createText']);
        Route::post('create/image', [App\Http\Controllers\Api\ChatController::class, 'createImage']);
        Route::post('create/video', [App\Http\Controllers\Api\ChatController::class, 'createVideo']);
        Route::post('create/audio', [App\Http\Controllers\Api\ChatController::class, 'createAudio']);
    });
    Route::group(['prefix' => 'payment-plans'], function () {
        Route::get('get', [App\Http\Controllers\Api\PaymentPlanController::class, 'getPlans']);
    });
});

