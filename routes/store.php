<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\CurrencyController;
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
use App\Http\Controllers\WeeklyMenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\FoodPackageController;

// Marketplace home hub
Route::get('/', [MarketplaceController::class, 'home'])->name('xylo.home');
Route::get('/home', [MarketplaceController::class, 'home']);
Route::get('/services', [MarketplaceController::class, 'services'])->name('services.index');
Route::get('/service/{slug}', [MarketplaceController::class, 'service'])->name('service.show');
Route::get('/vendors', [MarketplaceController::class, 'vendors'])->name('vendors.index');
Route::get('/vendor/{id}', [MarketplaceController::class, 'vendorShow'])
    ->whereNumber('id')
    ->name('vendor.show');
Route::get('/store/{slug}', [MarketplaceController::class, 'shopShow'])->name('store.show');

// Monthly food packages (replaces old pre-order flow)
Route::get('/food-packages', [FoodPackageController::class, 'index'])->name('packages.index');
Route::get('/food-packages/{slug}', [FoodPackageController::class, 'show'])->name('packages.show');
Route::post('/food-packages/add-to-cart', [FoodPackageController::class, 'addToCart'])->name('packages.add-to-cart');
Route::get('/pre-order', [FoodPackageController::class, 'index'])->name('pre-order');
Route::get('/monthly-food', [FoodPackageController::class, 'index'])->name('monthly-food');

// Food / menu flows
Route::get('/menus', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('xylo.menus');
Route::get('/menu', [WeeklyMenuController::class, 'showThreeDayMenu'])->name('menu');
Route::get('/cutoff-time', [WeeklyMenuController::class, 'getCutoffTime'])->name('cutoff.time');
Route::get('/regular-order', [WeeklyMenuController::class, 'regularOrderPage'])->name('regular-order');
Route::get('/regular-order/{slug}', [WeeklyMenuController::class, 'regularCategoryFilter']);

Route::get('/about-us', function () {
    return view('themes.xylo.about-us');
})->name('about');

Route::get('/contact-us', function () {
    return view('themes.xylo.contact-us');
})->name('contact');

Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::post('/payment/status', [PaymentController::class, 'checkPaymentStatus'])->name('payment.status');

Route::get('/categories', [StoreController::class, 'allcategories'])->name('categories.index');
Route::get('/products', [StoreController::class, 'allproducts'])->name('products.index');
Route::get('/category/products/{slug}', function ($slug) {
    return redirect()->route('products.index', ['category' => $slug]);
});

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/change-currency', [CurrencyController::class, 'changeCurrency'])->name('change.currency');
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
Route::get('/order/success', [PaymentController::class, 'success'])->name('order.success');
Route::post('/validate-customer', [ProductController::class, 'validateCustomer'])->name('validate-customer');
Route::post('/submit-review', [ProductController::class, 'submitReview'])->name('submit-review');

Route::prefix('customer')->name('customer.')->group(function () {
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

    Route::middleware('auth.customer')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    });
});

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::get('/cart', [WeeklyMenuController::class, 'cartPage'])->name('cart.page');

Route::get('/track-order', [OrderTrackingController::class, 'showTrackingPage'])->name('order.tracking');
Route::post('/track-order', [OrderTrackingController::class, 'trackOrder'])->name('order.track');
Route::get('/order-details/{orderId}', [OrderTrackingController::class, 'getOrderDetails'])->name('order.details');
