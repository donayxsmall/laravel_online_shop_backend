<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BannerSliderController;
// use App\Http\Controllers\Api\BannerSliderController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



//register
Route::post('/register', [AuthController::class, 'register']);
//login
Route::post('/login', [AuthController::class, 'login']);

//login Google
Route::post('/login-google', [AuthController::class, 'googleLogin']);

Route::get('/test-email', [AuthController::class, 'testEmail']);

//banner slider
Route::get('/banner-slider', [BannerSliderController::class, 'index']);
//category
Route::get('/categories', [CategoryController::class, 'index']);
//product
Route::get('/product', [ProductController::class, 'index']);

Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::group(['middleware' => 'auth:sanctum'], function () {
    //get user
    Route::get('/user', [AuthController::class, 'getUser']);
    //logout
    Route::post('/logout', [AuthController::class, 'logout']);
    //refresh
    Route::post('/refresh', [AuthController::class, 'refresh']);

    //Address
    Route::resource('address', AddressController::class);

    // set default address
    Route::put('address/set-default/{id}', [AddressController::class, 'setDefaultAddress']);

    // delete address
    // Route::delete('address/{id}', [AddressController::class, 'setDefaultAddress']);

    // get oreder
    Route::post('/order',[OrderController::class, 'order']);

    // check status order
    Route::get('/order/status/{id}',[OrderController::class, 'checkStatusOrder']);

    // get order by id
    Route::get('/order/{id}',[OrderController::class, 'getOrderById']);

    // get Order By User
    Route::get('/orders',[OrderController::class, 'getOrderByUser']);

    //udpate fcm id
    Route::post('/update-fcm', [AuthController::class, 'updateFcmId']);

    //udpate profile
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
});


Route::post('/callback',[CallbackController::class,'callback']);

Route::post('/test-notif',[OrderController::class,'testNotif']);


