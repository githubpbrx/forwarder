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
        Route::get('search', 'AllocationForwarder@create');
        Route::post('getsupplier/', 'AllocationForwarder@getsupplier')->name('allocation_getsupplier');

        // Route::get('getdetail/{id}', 'AllocationForwarder@show_detail')->name('detail_allocation');
        Route::post('/getdetail', 'AllocationForwarder@show_detail')->name('detail_allocation');
        Route::post('detailaction/', 'AllocationForwarder@store_detail')->name('detailaction');
        Route::post('getfwd/', 'AllocationForwarder@getforwarder')->name('get_forwarder');
    });

    Route::group(['prefix' => 'approval'], function () {
        Route::get('/', 'ApprovalConfirmation@index')->name('approvalconfirmation');
        Route::post('getsupplier/', 'ApprovalConfirmation@getsupplier')->name('get_supplier');
        Route::post('getbuyer/', 'ApprovalConfirmation@getbuyer')->name('get_buyer');
        Route::get('search', 'ApprovalConfirmation@search');
        Route::post('/getapproval', 'ApprovalConfirmation@getdataapproval')->name('getdataapproval');
        Route::post('/getdetail', 'ApprovalConfirmation@getdetailapproval')->name('getdetailapproval');
        Route::get('/getkaryawan/{id}', 'ApprovalConfirmation@getkaryawan')->name('approvalgetkaryawan');
        Route::post('statusapproval/{approval}', 'ApprovalConfirmation@statusapproval')->name('approvalstatus');
        Route::get('listapproval', 'ApprovalConfirmation@listapproval')->name('list_approval');
    });

    Route::group(['prefix' => 'shipment'], function () {
        Route::get('/', 'DataShipment@index')->name('datashipment');
        Route::get('/listshipment', 'DataShipment@listshipment')->name('list_shipment');
        Route::post('/getshipment', 'DataShipment@getdatashipment')->name('getdatashipment');
        Route::post('/updateshipment/', 'DataShipment@updateshipment')->name('updateshipment');
        // Route::post('statusapproval/{approval}', 'DataShipment@statusapproval')->name('approvalstatus');
        // Route::get('listapproval', 'DataShipment@listapproval')->name('list_approval');
    });
});
