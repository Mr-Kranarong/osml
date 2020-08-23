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

//Route for normal user
// Route::group(['middleware' => ['auth']], function () {
//     Route::get('/', 'HomeController@index');
// });
//Route for admin
// Route::group(['middleware' => ['admin']], function(){
//     Route::get('/dashboard', 'admin\AdminController@index');
// });