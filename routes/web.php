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
    Route::put('/user/{user}/update_self', 'UserController@update_self')->name('user.update_self');
});

//GUEST
Route::get('/product/{product}', 'ProductController@view')->name('product.view');

Route::get('/filter', 'HomeController@filter')->name('home.filter');
Route::get('/', 'HomeController@index')->name('home');
Route::get('lang/{locale}', 'HomeController@lang');
Auth::routes();