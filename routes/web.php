<?php

use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth')->group(function (){
    Route::get('stock/list','StockController@index')->name('stock.list');
    Route::get('stock/v','StockController@index')->name('stock.index');
    Route::get('stock/v/{city}','StockController@city')->name('stock.city');
    Route::get('stock/v/{city}/{item_code}','StockController@item')->name('stock.item');
    Route::get('stock/v/{city}/{item_code}/{hu2_no}','StockController@hu2')->name('stock.hu2');
    Route::get('stock/in','StockController@in')->name('stock.in');
    Route::post('stock/in','StockController@stock_in')->name('stock.stock_in');
    Route::get('stock/search','StockController@search')->name('stock.search');
    Route::post('stock/search','StockController@postSearch')->name('stock.postSearch');

    Route::get('stock/out',function (){
        return view('stock.out');
    });

    Route::get('ajax/stock/search','AjaxStockController@search')->name('ajax.stock.search');
    Route::post('ajax/stock/outToCanvasser','AjaxStockController@outToCanvasser')->name('ajax.stock.outToCanvasser');
});


Route::get('/home', 'HomeController@index')->name('home');
