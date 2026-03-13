<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CreditcardsController;
use App\Http\Controllers\FundsController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->type === 'Customer') {
            return view('customer.welcome');
        } elseif ($user->type === 'Merchant') {
            return view('merchant.welcome');
        } elseif ($user->type === 'Store') {
            return view('store.welcome');
        } else {
            return view('admin.welcome');
        }
    }
    

    return view('welcome');
})->name('home');

Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
Route::get('/signin', [AuthManager::class, 'signin'])->name('signin');
Route::post('/signin', [AuthManager::class, 'signinPost'])->name('signin.post');
Route::get('/logout', [AuthManager::class, 'logOut'])->name('logout');

Route::resource('creditcards', CreditcardsController::class);
Route::get('/funds', [FundsController::class, 'index'])->name('funds.index');
Route::post('/funds/transfer', [FundsController::class, 'transfer'])->name('funds.transfer');