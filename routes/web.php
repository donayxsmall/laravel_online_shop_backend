<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     if(Auth::check()){
//         return view('pages.dashboard');
//     }else{
//         return view('pages.auth.login');
//     }
// });

Route::get('/', function () {
    if(Auth::check()){
        return view('pages.dashboard');
    }else{
        return view('pages.auth.login');

        // echo "sdad";
        // echo fake()->imageUrl();
    }
});

// Route::middleware(['prevent-back-history'])->group(function () {

// });

Route::middleware(['auth'])->group(function () {

    // Auth::routes();

    Route::get('/home', function () {
        return view('pages.dashboard',['type_menu' => 'dashboard']);
    })->name('home');

    Route::resource('user', UserController::class);

    // Route::delete('/user/{id}', [UserController::class,'destroy'])->name('user.destroy');

    Route::get('get-user',[UserController::class,'getUser']);

    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);
});
