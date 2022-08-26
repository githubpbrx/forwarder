<?php

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

Route::prefix('transaksi')->group(function () {
    Route::get('/', 'TransaksiController@index');

    Route::group(['prefix' => 'allocation'], function () {
        Route::get('/', 'AllocationForwarder@index')->name('allocationforwarder');
    });

    Route::group(['prefix' => 'approval'], function () {
        Route::get('/', 'ApprovalConfirmation@index')->name('approvalconfirmation');
    });
});
