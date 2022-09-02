<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','is-active'])->name('dashboard');
Route::group(['middleware' => ['auth' , 'is-active']] , function(){

});

require __DIR__.'/auth.php';

Auth::routes();

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

