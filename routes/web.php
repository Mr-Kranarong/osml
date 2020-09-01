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

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::get('lang/{locale}', 'HomeController@lang');


Route::put('/user/{user}/update_self', 'UserController@update_self')->name('user.update_self');

Route::middleware(['admin'])->group(function(){
    Route::post('/product/category/create', 'ProductController@category_create')->name('product.category.create');
    Route::delete('/product/category/{category}', 'ProductController@category_delete')->name('product.category.delete');
    Route::put('/product/category/{category}', 'ProductController@category_update')->name('product.category.update');
    Route::get('/product/category', 'ProductController@category_index')->name('product.category.index');
    
    Route::delete('/product', 'ProductController@delete')->name('product.delete');
    Route::post('/product', 'ProductController@search')->name('product.search');
    Route::get('/product', 'ProductController@index')->name('product.index');

    Route::post('/user', 'UserController@search')->name('user.search');
    Route::delete('/user/{user}', 'UserController@delete')->name('user.delete');
    Route::put('/user/{user}', 'UserController@update')->name('user.update');
    Route::get('/user', 'UserController@index')->name('user.index');
});

//Route for normal user
// Route::group(['middleware' => ['auth']], function () {
//     Route::get('/', 'HomeController@index');
// });
//Route for admin
// Route::group(['middleware' => ['admin']], function(){
//     Route::get('/dashboard', 'admin\AdminController@index');
// });