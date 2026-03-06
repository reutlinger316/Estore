<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');

Route::get('/signin', [AuthManager::class, 'signin'])->name('signin');
Route::post('/signin', [AuthManager::class, 'signinPost'])->name('signin.post');

Route::get('/logout', [AuthManager::class, 'logOut'])->name('logout');

Route::group(['middleware'=>'auth'], function() {
    Route::get('/profile', function() {
        return "Hi";
    });
});