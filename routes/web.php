<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SocialMediaLinkController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/admin/login', function () {
    return view('admin.auth.login');
});
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login');
Route::get('/test', function () {
    return view('tests');
});
Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/weekly-menu', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'index'])->name('admin.weeklymenu.index');

    Route::get('/weekly-menu/create', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'create'])->name('admin.weeklymenu.create');

    Route::post('/weekly-menu/store', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'store'])->name('admin.weeklymenu.store');

    Route::get('/weekly-menu/{id}/edit', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'edit'])->name('admin.weeklymenu.edit');

    Route::post('/weekly-menu/{id}/update', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'update'])->name('admin.weeklymenu.update');

    Route::delete('/weekly-menu/{id}', [App\Http\Controllers\Admin\WeeklyMenuController::class, 'destroy'])->name('admin.weeklymenu.delete');

});


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::post('products/data', [ProductController::class, 'getProducts'])->name('products.data');
    Route::post('admin/products/updateStatus', [ProductController::class, 'updateStatus'])->name('products.updateStatus');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


    /* Brands */
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('admin/brands/getdata', [BrandController::class, 'getData'])->name('brands.getData');
    Route::get('brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update'); 
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::post('brands/update-status', [BrandController::class, 'updateStatus'])->name('brands.updateStatus');

    Route::post('/change-language', [LanguageController::class, 'changeLanguage'])->name('change.language');

    Route::resource('menus', MenuController::class);
    Route::post('menus/data', [MenuController::class, 'getData'])->name('menus.data');
    Route::resource('menus.items', MenuItemController::class)->shallow();
    Route::get('menus-items', [MenuItemController::class, 'index'])->name('menus.item.index');
    Route::post('menus-items/getdata', [MenuItemController::class, 'getData'])->name('menus.item.getData');
    Route::delete('/products/delete-image/{image}', [ProductController::class, 'deleteImage'])
    ->name('products.delete-image');


});

Route::post('/categories/data', [CategoryController::class, 'getCategories'])->name('categories.data');
Route::post('/admin/categories/update-status', [CategoryController::class, 'updateCategoryStatus'])->name('admin.categories.updateStatus');






Route::get('site-settings', [SiteSettingsController::class, 'index'])->name('site-settings.index');
Route::get('site-settings/edit', [SiteSettingsController::class, 'edit'])->name('admin.site-settings.edit');
Route::put('site-settings/update', [SiteSettingsController::class, 'update'])->name('admin.site-settings.update');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', BannerController::class);
    Route::post('banners/data', [BannerController::class, 'getData'])->name('banners.data');
    Route::put('/banners/toggle-status/{id}', [BannerController::class, 'toggleStatus'])->name('banners.toggleStatus');
    Route::post('/banners/update-status', [BannerController::class, 'updateStatus'])->name('banners.updateStatus');

}); 

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('social-media-links', SocialMediaLinkController::class);
    Route::post('social-media-links/data', [SocialMediaLinkController::class, 'getData'])->name('social-media-links.data');
});


// Route::prefix('admin')->name('admin.')->middleware('auth')->group(function() {
//     Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
//     Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
//     Route::post('orders/data', [OrderController::class, 'getData'])->name('orders.data');
//     Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])
//     ->name('orders.updateStatus');
//     Route::get('/orders/{id}', [OrderController::class, 'show'])
//     ->name('orders.show');
// });


Route::prefix('admin')->name('admin.')->middleware( 'auth')->group(function () {
    // Product Variant Routes
    Route::resource('product_variants', ProductVariantController::class);
    Route::post('/product_variants/data', [ProductVariantController::class, 'getData'])->name('product_variants.data');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::get('admin/customers/data', [CustomerController::class, 'getCustomerData'])->name('customers.data');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/reviews/data', [ProductReviewController::class, 'getData'])->name('reviews.data');
    Route::resource('reviews', ProductReviewController::class)->except(['create', 'store']);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('sellers', SellerController::class);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('attributes', AttributeController::class);
    // Attribute Value Management
    Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('values/{value}', [AttributeController::class, 'destroyValue'])->name('values.destroy');
    Route::post('attributes/data', [AttributeController::class, 'getAttributesData'])->name('attributes.data');

    // Attribute Value Translations Management
    Route::post('values/{value}/translations', [AttributeController::class, 'storeTranslation'])->name('values.translations.store');
    Route::delete('translations/{translation}', [AttributeController::class, 'destroyTranslation'])->name('translations.destroy');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/order-items/{orderItem}/status', [OrderController::class, 'updateItemStatus'])->name('admin.orders.update-item-status');
    Route::get('/kitchen-display', [OrderController::class, 'kitchenDisplay'])->name('admin.orders.kitchen');
    Route::get('/delivery-schedule', [OrderController::class, 'deliverySchedule'])->name('admin.orders.delivery-schedule');
});
Route::get('/admin/dashboard/stats', [HomeController::class, 'getDashboardStats'])->name('admin.dashboard.stats');