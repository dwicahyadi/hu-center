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

Route::middleware('auth')->group(function (){
    Route::get('stock/list','StockController@index')->name('stock.list');
    Route::get('stock/hu2','StockController@hu2')->name('stock.hu2');
    Route::get('stock/hu1/{hu2_no}','StockController@hu1')->name('stock.hu1');
    Route::get('stock/hu1/{hu2_no}/{hu1_no}','StockController@box')->name('stock.box');
    Route::get('stock/in','StockController@in')->name('stock.in');
    Route::post('stock/in','StockController@stock_in')->name('stock.stock_in');
    Route::get('stock/search','StockController@search')->name('stock.search');
    Route::post('stock/search','StockController@postSearch')->name('stock.postSearch');
});


Route::get('/home', 'HomeController@index')->name('home');
