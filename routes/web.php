<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\StoreFrontController;
use App\Http\Controllers\MerchantStoreFrontController;
use App\Http\Controllers\MerchantItemController;

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
        ->name('customer.creditcards.storefront');
    Route::delete('/customer/creditcards/{creditcard}', [CreditCardController::class, 'destroy'])
        ->name('customer.creditcards.destroy');

    Route::get('/customer/funds', [FundsController::class, 'index'])
        ->name('customer.funds.index');
    Route::post('/customer/funds/transfer', [FundsController::class, 'transfer'])
        ->name('customer.funds.transfer');
});

Route::middleware(['auth', 'active', 'role:merchant'])->group(function () {
    Route::get('/merchant/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');

    Route::get('/merchant/storefronts', [MerchantStoreFrontController::class, 'index'])->name('merchant.storefronts.index');
    Route::get('/merchant/storefronts/create', [MerchantStoreFrontController::class, 'create'])->name('merchant.storefronts.create');
    Route::post('/merchant/storefronts', [MerchantStoreFrontController::class, 'store'])->name('merchant.storefronts.storefront');
    Route::delete('/merchant/storefronts/{storeFront}', [MerchantStoreFrontController::class, 'destroy'])->name('merchant.storefronts.destroy');

    Route::get('/merchant/storefronts/{storeFront}/items', [MerchantItemController::class, 'index'])->name('merchant.items.index');
    Route::get('/merchant/storefronts/{storeFront}/items/create', [MerchantItemController::class, 'create'])->name('merchant.items.create');
    Route::post('/merchant/storefronts/{storeFront}/items', [MerchantItemController::class, 'storefront'])->name('merchant.items.storefront');
    Route::get('/merchant/storefronts/{storeFront}/items/{item}/edit', [MerchantItemController::class, 'edit'])->name('merchant.items.edit');
    Route::put('/merchant/storefronts/{storeFront}/items/{item}', [MerchantItemController::class, 'update'])->name('merchant.items.update');
    Route::delete('/merchant/storefronts/{storeFront}/items/{item}', [MerchantItemController::class, 'destroy'])->name('merchant.items.destroy');

    Route::get('/merchant/storefronts/{storeFront}/discounts', [MerchantDiscountController::class, 'index'])->name('merchant.discounts.index');
    Route::get('/merchant/storefronts/{storeFront}/discounts/create', [MerchantDiscountController::class, 'create'])->name('merchant.discounts.create');
    Route::post('/merchant/storefronts/{storeFront}/discounts', [MerchantDiscountController::class, 'store'])->name('merchant.discounts.store');
    Route::get('/merchant/storefronts/{storeFront}/discounts/{discount}/edit', [MerchantDiscountController::class, 'edit'])->name('merchant.discounts.edit');
    Route::put('/merchant/storefronts/{storeFront}/discounts/{discount}', [MerchantDiscountController::class, 'update'])->name('merchant.discounts.update');
    Route::delete('/merchant/storefronts/{storeFront}/discounts/{discount}', [MerchantDiscountController::class, 'destroy'])->name('merchant.discounts.destroy');
});

Route::middleware(['auth', 'active', 'role:storefront'])->group(function () {
    Route::get('/storefront/dashboard', [StoreFrontController::class, 'dashboard'])->name('storefront.dashboard');
    Route::get('/storefront/branch-requests', [StoreFrontController::class, 'branchRequests'])->name('storefront.branch-requests');
    Route::post('/storefront/branch-requests/{storeFront}/accept', [StoreFrontController::class, 'acceptBranch'])->name('storefront.branch-requests.accept');
    Route::post('/storefront/branch-requests/{storeFront}/reject', [StoreFrontController::class, 'rejectBranch'])->name('storefront.branch-requests.reject');
});
