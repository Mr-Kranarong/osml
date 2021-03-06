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

//ADMIN
Route::middleware(['admin'])->group(function(){
    Route::post('/statistic/stocks', 'StatisticController@getLowStock')->name('statistic.stocks');
    Route::post('/statistic/categories', 'StatisticController@getCategoryCount')->name('statistic.categories');
    Route::post('/statistic/sales', 'StatisticController@getTopSold')->name('statistic.sales');
    Route::post('/statistic/wishlists', 'StatisticController@getTopWishlist')->name('statistic.wishlists');
    Route::post('/statistic/views', 'StatisticController@getTopView')->name('statistic.views');
    Route::post('/statistic/profit', 'StatisticController@getSalesProfit')->name('statistic.profit');
    Route::get('/statistic', 'StatisticController@index')->name('statistic.index');

    Route::put('/settings/{setting}', 'SettingsController@update')->name('settings.update');
    Route::get('/settings', 'SettingsController@index')->name('settings.index');

    Route::delete('/inquiry', 'ProductQuestionController@delete')->name('question.delete');
    Route::post('/inquiry', 'ProductQuestionController@answer')->name('question.answer');
    Route::get('/inquiry', 'ProductQuestionController@index')->name('question.index');

    Route::post('/order/refunded', 'PurchaseOrderController@refunded')->name('po.refunded');
    Route::post('/order/processed', 'PurchaseOrderController@processed')->name('po.processed');

    Route::get('/promotion', 'ProductPromotionController@index')->name('product.promotion.index');
    Route::post('/promotion', 'ProductPromotionController@store')->name('product.promotion.store');
    Route::delete('/promotion', 'ProductPromotionController@delete')->name('product.promotion.delete');
    Route::get('/promotion/apriori', 'ProductPromotionController@apriori')->name('product.promotion.apriori');
    Route::get('/promotion/{product}', 'ProductPromotionController@create')->name('product.promotion.create');

    Route::post('/product/category/create', 'ProductController@category_create')->name('product.category.create');
    Route::delete('/product/category/{category}', 'ProductController@category_delete')->name('product.category.delete');
    Route::put('/product/category/{category}', 'ProductController@category_update')->name('product.category.update');
    Route::get('/product/category', 'ProductController@category_index')->name('product.category.index');

    Route::post('/product/create', 'ProductController@store')->name('product.store');
    Route::get('/product/create', 'ProductController@create')->name('product.create');
    Route::delete('/product/{product}/delete_image', 'ProductController@delete_image')->name('product.delete_image');
    Route::put('/product/{product}/edit', 'ProductController@update')->name('product.update');
    Route::get('/product/{product}/edit', 'ProductController@edit')->name('product.edit');
    Route::delete('/product', 'ProductController@delete')->name('product.delete');
    Route::post('/product', 'ProductController@search')->name('product.search');
    Route::get('/product', 'ProductController@index')->name('product.index');

    Route::post('/user', 'UserController@search')->name('user.search');
    Route::delete('/user/{user}', 'UserController@delete')->name('user.delete');
    Route::put('/user/{user}', 'UserController@update')->name('user.update');
    Route::get('/user', 'UserController@index')->name('user.index');

    Route::put('/coupon/{coupon}', 'CouponController@update')->name('coupon.update');
    Route::delete('/coupon/{coupon}', 'CouponController@delete')->name('coupon.delete');
    Route::post('/coupon', 'CouponController@create')->name('coupon.create');
    Route::get('/coupon', 'CouponController@index')->name('coupon.index');
});

//USER
Route::middleware(['auth'])->group(function(){
    Route::post('/question', 'ProductQuestionController@create')->name('question.create');

    Route::post('/review', 'ProductReviewController@create')->name('review.create');

    Route::get('/order', 'PurchaseOrderController@index')->name('po.index');

    Route::post('/coupon/use', 'CouponController@use')->name('coupon.use');

    Route::put('/user/{user}/update_self', 'UserController@update_self')->name('user.update_self');

    Route::delete('/favorite/ajax', 'FavoriteController@remove')->name('favorite.remove');
    Route::post('/favorite/ajax', 'FavoriteController@add')->name('favorite.add');
    Route::delete('/favorite', 'FavoriteController@delete')->name('favorite.delete');
    Route::get('/favorite', 'FavoriteController@index')->name('favorite.index');
});

//GUEST
Route::get('/product/{product}', 'ProductController@view')->name('product.view');

Route::get('/order/{po_id}/export', 'PurchaseOrderController@export2pdf')->name('po.export2pdf');
Route::get('/order/{po_id}', 'PurchaseOrderController@view')->name('po.view');

Route::get('/cart/transaction/{orderID}', 'CartController@transaction_completed')->name('cart.transaction_completed');
Route::post('/cart/address', 'CartController@address_session')->name('cart.guest_address');
Route::post('/cart/coupon', 'CartController@coupon_session')->name('cart.coupon');
Route::get('/cart/finalize', 'CartController@finalize')->name('cart.finalize');
Route::get('/cart/delete/{chk_id}', 'CartController@remove')->name('cart.remove_single');
Route::put('/cart', 'CartController@update')->name('cart.update');
Route::delete('/cart', 'CartController@remove')->name('cart.remove');
Route::post('/cart/bundle', 'CartController@bundle')->name('cart.bundle');
Route::post('/cart', 'CartController@add')->name('cart.add');
Route::get('/cart', 'CartController@index')->name('cart.index');

Route::get('/filter', 'HomeController@filter')->name('home.filter');
Route::get('/', 'HomeController@index')->name('home');
Route::get('lang/{locale}', 'HomeController@lang');
Auth::routes();
