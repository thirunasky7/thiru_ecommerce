<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\CurrencyController;
//use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\ShopController;
use App\Http\Controllers\Store\SearchController;
use App\Http\Controllers\Admin\LanguageController;

use App\Http\Controllers\Store\Auth\LoginController;
use App\Http\Controllers\Store\Auth\RegisterController;
use App\Http\Controllers\Store\Auth\ForgotPasswordController;
use App\Http\Controllers\Store\Auth\ResetPasswordController;
use App\Http\Controllers\Store\WishlistController;

use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\PaymentController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\CartController;




Route::get('/', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('xylo.home');
Route::get('/menus', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('xylo.menus');
Route::get('/menu', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('menu');
Route::get('/cutoff-time', [WeeklyMenuController::class, 'getCutoffTime'])->name('cutoff.time');
Route::get('/debug-menu/{day}', [WeeklyMenuController::class, 'debugMenu']);

Route::get('/home', [WeeklyMenuController::class, 'showThreeDayMenu']);
Route::get('/services', function(){
    return view('services');
});
Route::get('/about-us', function(){
    return view('about-us');
});


Route::get('/contact-us', function(){
    return view('contact-us');
});

Route::post('/payment/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');

Route::get('/categories', [StoreController::class, 'allcategories'])->name('categories.index');
Route::get('/products', [StoreController::class, 'allproducts'])->name('products.index');
Route::get('/category/products/{slug}', function ($slug) {
    return redirect()->route('products.index', ['category' => $slug]);
});

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/change-currency', [CurrencyController::class, 'changeCurrency'])->name('change.currency');

// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
// Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
// Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::post('/change-store-language', [LanguageController::class, 'changeLanguage'])->name('change.store.language');

Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

Route::get('/search-suggestions', [SearchController::class, 'suggestions']);
Route::get('/search', [SearchController::class, 'searchResults']);

Route::get('/get-variant-price', [ProductController::class, 'getVariantPrice'])->name('product.variant.price');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::post('/payment/initiate/upi', [PaymentController::class, 'initiateUpiPayment'])->name('payment.initiate.upi');
Route::post('/payment/status', [PaymentController::class, 'checkPaymentStatus'])->name('payment.status');
Route::get('/order/success', [PaymentController::class, 'success'])->name('order.success');
Route::post('/validate-customer', [ProductController::class, 'validateCustomer'])->name('validate-customer');
Route::post('/submit-review', [ProductController::class, 'submitReview'])->name('submit-review');

// routes/web.php
Route::get('/track-orders', [OrderHistoryController::class, 'showForm'])->name('orders.form');
Route::post('/track-orders', [OrderHistoryController::class, 'fetchOrders'])->name('orders.fetch');


Route::prefix('customer')->name('customer.')->group(function () {
    
    // Guest routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
        
        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);

        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    }); 

    // Authenticated routes
    Route::middleware('auth.customer')->group(function () { 
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        /*Route::get('/', fn() => view('themes.xylo.home'))->name('dashboard');*/
        Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    });
});

// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
// Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
// Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
// // Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::get('/cart', [WeeklyMenuController::class, 'cartPage'])->name('cart');
// routes/web.php
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::get('/cart', [WeeklyMenuController::class, 'cartPage'])->name('cart.page');
Route::get('/menu', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('menu');

