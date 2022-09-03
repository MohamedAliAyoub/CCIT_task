<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['auth' , 'is-active']] , function(){

});

require __DIR__.'/auth.php';

Auth::routes(['verify' => true]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// facebook
Route::get('login/facebook', [App\Http\Controllers\Auth\LoginController::class , 'redirectToFacebook'])->name('facebook.login');
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class , 'handleFacebookCallback'])->name('login.facebook');

// google
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class , 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class , 'handleGoogleCallback'])->name('login.google');

// github
Route::get('login/github', [App\Http\Controllers\Auth\LoginController::class , 'redirectToGithub'])->name('github.login');
Route::get('login/github/callback', [App\Http\Controllers\Auth\LoginController::class , 'handleGithubCallback'])->name('login.github');


Route::group(['prefix'=>'admin', 'middleware'=>['isAdmin','auth','PreventBackHistory']], function(){
    Route::get('dashboard',[AdminController::class,'index'])->name('admin.dashboard');
    Route::get('profile',[AdminController::class,'profile'])->name('admin.profile');
    Route::get('users',[AdminController::class,'users'])->name('admin.users');


    Route::post('update-profile-info',[AdminController::class,'updateInfo'])->name('adminUpdateInfo');
    Route::post('change-profile-picture',[AdminController::class,'updatePicture'])->name('adminPictureUpdate');
    Route::post('change-password',[AdminController::class,'changePassword'])->name('adminChangePassword');


    Route::get('/users', [AdminController::class, 'index'])->name('user.index');
    Route::get('/users/delete/{id}', [AdminController::class, 'destroy'])->name('user.delte');
    Route::get('/users/mange-block/{id}', [AdminController::class, 'manage_block'])->name('user.manage_block');
    Route::get('/users/search', [AdminController::class, 'search'])->name('user.search');
    Route::get('users',[AdminController::class,'users'])->name('admin.users');


});

Route::group(['prefix'=>'user', 'middleware'=>['isUser','auth','PreventBackHistory']], function(){
    Route::get('dashboard',[UserController::class,'index'])->name('user.dashboard');
    Route::get('profile',[UserController::class,'profile'])->name('user.profile');
    Route::get('settings',[UserController::class,'settings'])->name('user.settings');

    Route::get('subscription/create', [SubscriptionController::class , 'index'])->name('user.subscription.create');
    Route::post('order-post', [SubscriptionController::class , 'orderPost'])->name('user.subscription.order-post');

});
