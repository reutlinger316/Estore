<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerShopController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\MerchantDiscountController;
use App\Http\Controllers\MerchantItemController;
use App\Http\Controllers\MerchantStoreFrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreFrontController;
use App\Http\Controllers\StoreFrontOrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ItemReviewController;
use App\Http\Controllers\MerchantStoreFrontPerformanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RestockRequestController;

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
    Route::get('/customer/shops', [CustomerShopController::class, 'index'])->name('customer.shops.index');
    Route::get('/customer/shops/{storeFront}', [CustomerShopController::class, 'show'])->name('customer.shops.show');
    Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/customer/cart/add/{item}', [CartController::class, 'add'])->name('customer.cart.add');
    Route::delete('/customer/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('customer.cart.remove');
    Route::post('/customer/cart/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');
    Route::get('/customer/orders', [OrderController::class, 'index'])->name('customer.orders.index');

    
    Route::get('/customer/storefronts/{storeFront}/reviews/create', [ReviewController::class, 'create'])
        ->name('customer.reviews.create');
    Route::post('/customer/storefronts/{storeFront}/reviews', [ReviewController::class, 'store'])
        ->name('customer.reviews.store');
    Route::get('/customer/storefronts/{storeFront}/reviews', [ReviewController::class, 'index'])
        ->name('customer.reviews.index');
    Route::get('/customer/reviews/{review}/edit', [ReviewController::class, 'edit'])
        ->name('customer.reviews.edit');
    Route::put('/customer/reviews/{review}', [ReviewController::class, 'update'])
        ->name('customer.reviews.update');
    Route::delete('/customer/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('customer.reviews.destroy');


    Route::get('/customer/items/{item}/reviews/create', [ItemReviewController::class, 'create'])
        ->name('customer.item-reviews.create');
    Route::post('/customer/items/{item}/reviews', [ItemReviewController::class, 'store'])
        ->name('customer.item-reviews.store');
    Route::get('/customer/items/{item}/reviews', [ItemReviewController::class, 'index'])
        ->name('customer.item-reviews.index');
    Route::get('/customer/item-reviews/{itemReview}/edit', [ItemReviewController::class, 'edit'])
        ->name('customer.item-reviews.edit');
    Route::put('/customer/item-reviews/{itemReview}', [ItemReviewController::class, 'update'])
        ->name('customer.item-reviews.update');
    Route::delete('/customer/item-reviews/{itemReview}', [ItemReviewController::class, 'destroy'])
        ->name('customer.item-reviews.destroy');
    Route::get('/customer/orders/{order}/receipt', [ReceiptController::class, 'customerShow'])
    ->name('customer.receipts.show');

});

Route::middleware(['auth', 'active', 'role:merchant'])->group(function () {
    Route::get('/merchant/dashboard', [MerchantController::class, 'dashboard'])->name('merchant.dashboard');
    Route::get('/merchant/storefronts', [MerchantStoreFrontController::class, 'index'])->name('merchant.storefronts.index');
    Route::get('/merchant/storefronts/create', [MerchantStoreFrontController::class, 'create'])->name('merchant.storefronts.create');
    Route::post('/merchant/storefronts', [MerchantStoreFrontController::class, 'store'])->name('merchant.storefronts.store');

    Route::get('/merchant/storefronts/{storeFront}/items', [MerchantItemController::class, 'index'])->name('merchant.items.index');
    Route::get('/merchant/storefronts/{storeFront}/items/create', [MerchantItemController::class, 'create'])->name('merchant.items.create');
    Route::post('/merchant/storefronts/{storeFront}/items', [MerchantItemController::class, 'store'])->name('merchant.items.store');
    Route::delete('/merchant/storefronts/{storeFront}', [MerchantStoreFrontController::class, 'destroy'])->name('merchant.storefronts.destroy');
    Route::get('/merchant/storefronts/{storeFront}/items/{item}/edit', [MerchantItemController::class, 'edit'])->name('merchant.items.edit');
    Route::put('/merchant/storefronts/{storeFront}/items/{item}', [MerchantItemController::class, 'update'])->name('merchant.items.update');
    Route::delete('/merchant/storefronts/{storeFront}/items/{item}', [MerchantItemController::class, 'destroy'])->name('merchant.items.destroy');
    Route::get('/merchant/discounts', [MerchantDiscountController::class, 'index'])->name('merchant.discounts.index');
    Route::post('/merchant/items/{item}/discount', [MerchantDiscountController::class, 'updateItemDiscount'])->name('merchant.discounts.items.update');
    Route::post('/merchant/storefronts/{storeFront}/discount', [MerchantDiscountController::class, 'updateBranchDiscount'])->name('merchant.discounts.storefronts.update');
    Route::post('/merchant/discounts/global', [MerchantDiscountController::class, 'updateGlobalDiscount'])->name('merchant.discounts.global.update');
    Route::post('/merchant/storefronts/{storeFront}/transfer-balance', [MerchantStoreFrontController::class, 'transferBalanceToMerchant'])->name('merchant.storefronts.transfer-balance');

    Route::get('/merchant/restock-requests', [RestockRequestController::class, 'index'])->name('merchant.restock-requests.index');
    Route::post('/merchant/restock-requests/{restockRequest}/status', [RestockRequestController::class, 'updateStatus'])->name('merchant.restock-requests.status.update');

    Route::get('/merchant/performance', [MerchantStoreFrontPerformanceController::class, 'index'])->name('merchant.performance.index');
    Route::get('/merchant/performance/{storeFront}', [MerchantStoreFrontPerformanceController::class, 'show'])->name('merchant.performance.show');
    Route::get('/merchant/performance/{storeFront}/ratings', [MerchantStoreFrontPerformanceController::class, 'ratings'])->name('merchant.performance.ratings');
    Route::get('/merchant/performance/{storeFront}/orders', [MerchantStoreFrontPerformanceController::class, 'orders'])->name('merchant.performance.orders');
    
});


Route::middleware(['auth', 'active', 'role:storefront'])->group(function () {
    Route::get('/storefront/dashboard', [StoreFrontController::class, 'dashboard'])->name('storefront.dashboard');
    Route::get('/storefront/branch-requests', [StoreFrontController::class, 'branchRequests'])->name('storefront.branch-requests');
    Route::post('/storefront/branch-requests/{storeFront}/accept', [StoreFrontController::class, 'acceptBranch'])->name('storefront.branch-requests.accept');
    Route::post('/storefront/branch-requests/{storeFront}/reject', [StoreFrontController::class, 'rejectBranch'])->name('storefront.branch-requests.reject');

    Route::get('/storefront/orders', [StoreFrontOrderController::class, 'index'])->name('storefront.orders.index');
    Route::get('/storefront/branches/{storeFront}/restock-requests/create', [RestockRequestController::class, 'create'])->name('storefront.restock-requests.create');
    Route::post('/storefront/branches/{storeFront}/restock-requests', [RestockRequestController::class, 'store'])->name('storefront.restock-requests.store');
    Route::post('/storefront/orders/{order}/status', [StoreFrontOrderController::class, 'updateStatus'])->name('storefront.orders.status.update');
    Route::get('/storefront/orders/{order}/receipt', [ReceiptController::class, 'storefrontShow'])
    ->name('storefront.receipts.show');
});
Route::middleware(['auth', 'active', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
});