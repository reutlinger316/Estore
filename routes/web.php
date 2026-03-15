<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\FundsController;

Route::get('/', function () {
    if (auth()->check()) {

        return match(auth()->user()->role) {
            'admin' => redirect('/admin/dashboard'),
            'merchant' => redirect('/merchant/dashboard'),
            'storefront' => redirect('/storefront/dashboard'),
            default => redirect('/customer/dashboard'),
        };

    }

    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'active', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
        ->name('customer.dashboard');

    Route::get('/customer/creditcards', [CreditCardController::class, 'index'])
        ->name('customer.creditcards.index');
    Route::get('/customer/creditcards/create', [CreditCardController::class, 'create'])
        ->name('customer.creditcards.create');
    Route::post('/customer/creditcards', [CreditCardController::class, 'store'])
        ->name('customer.creditcards.store');
    Route::delete('/customer/creditcards/{creditcard}', [CreditCardController::class, 'destroy'])
        ->name('customer.creditcards.destroy');

    Route::get('/customer/funds', [FundsController::class, 'index'])
        ->name('customer.funds.index');
    Route::post('/customer/funds/transfer', [FundsController::class, 'transfer'])
        ->name('customer.funds.transfer');
});