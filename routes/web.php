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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('stock','StockController@index')->name('stock.list');
Route::get('stock/in','StockController@in')->name('stock.in');
Route::post('stock/in','StockController@stock_in')->name('stock.stock_in');

Route::get('/home', 'HomeController@index')->name('home');
