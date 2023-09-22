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

Route::prefix('transaksi')->group(function () {
    Route::get('/', 'TransaksiController@index');

    Route::group(['prefix' => 'allocation'], function () {
        Route::get('/', 'DataAllocation@index')->name('data_allocation');
        Route::get('datatables', 'DataAllocation@datatables');
        Route::get('cancelallocation/{id}/{idfwd}', 'DataAllocation@cancelallocation')->name('allocation_cancelallocation');

        Route::post('getsupplier/', 'DataAllocation@getsupplier')->name('allocation_getsupplier');
        Route::post('/getdetail', 'DataAllocation@show_detail')->name('allocation_detail');
        Route::post('detailaction/', 'DataAllocation@store_detail')->name('detailaction');
        Route::post('getfwd/', 'DataAllocation@getforwarder')->name('get_forwarder');
        Route::post('movefwd/', 'DataAllocation@movefwd')->name('allocation_movefwd');
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

    Route::group(['prefix' => 'shipmentprocess'], function () {
        Route::get('/', 'ProcessShipment@index')->name('process_shipment');
        Route::get('/listshipmentprocess', 'ProcessShipment@listshipmentprocess')->name('list_shipmentprocess');
        Route::post('/formshipmentprocess', 'ProcessShipment@formshipment')->name('form_shipmentprocess');
        Route::post('/saveshipmentprocess', 'ProcessShipment@saveshipment')->name('saveshipmentprocess');
        Route::post('/getportloading', 'ProcessShipment@getportloading')->name('getportloading');
        Route::post('/getportdestination', 'ProcessShipment@getportdestination')->name('getportdestination');
    });

    Route::group(['prefix' => 'updatebooking'], function () {
        Route::get('/', 'DataUpdateBooking@index')->name('dataupdatebooking');
        Route::get('/listbooking', 'DataUpdateBooking@listbooking')->name('list_booking');
        Route::post('/getbooking', 'DataUpdateBooking@getdatabooking')->name('getdatabooking');
        Route::post('/updatebooking/', 'DataUpdateBooking@updatebooking')->name('updatebooking');
    });

    Route::group(['prefix' => 'mappingratefcl'], function () {
        Route::get('/', 'MappingRate@index')->name('mappingratefcl');
        Route::get('/listmapping', 'MappingRate@listmapping')->name('list_mappingratefcl');
        Route::post('/mappingrateadd', 'MappingRate@add')->name('mappingratefcl_add');
        Route::post('/getcountry', 'MappingRate@getcountry')->name('getcountry');
        Route::post('/getpolcity', 'MappingRate@getpolcity')->name('getpolcity');
        Route::post('/getpodcity', 'MappingRate@getpodcity')->name('getpodcity');
        Route::post('/getshipping', 'MappingRate@getshipping')->name('getshipping');
        Route::post('/mappingrateedit', 'MappingRate@edit')->name('mappingratefcl_edit');
        Route::post('/updatemappingrate', 'MappingRate@update')->name('mappingratefcl_update');
        Route::get('/deletemappingratefcl/{id}', 'MappingRate@destroy')->name('mappingratefcl_delete');
    });

    Route::group(['prefix' => 'inputratefcl'], function () {
        Route::get('/', 'InputRate@index')->name('inputratefcl');
        Route::get('/listinput', 'InputRate@listinput')->name('list_inputratefcl');
        Route::post('/getinputrate', 'InputRate@getdatainputrate')->name('getdatainputrate');
        Route::post('/inputrateadd', 'InputRate@add')->name('inputratefcl_add');
    });
});
