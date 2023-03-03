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

Route::prefix('master')->group(function () {
    Route::get('/', 'MasterController@index');

    Route::group(['prefix' => 'forwarder'], function () {
        Route::get('/', 'MasterForwarder@index')->name('masterforwarder');
        Route::get('/getkaryawan/{id}', 'MasterForwarder@getkaryawan')->name('getkaryawan');
        Route::get('/listforwarder', 'MasterForwarder@listforwarder')->name('list_forwarder');
        Route::post('/fwdedit', 'MasterForwarder@editfwd')->name('masterfwd_edit');
        Route::post('/savefwd', 'MasterForwarder@savefwd')->name('masterfwd_save');
        Route::post('/updatefwd', 'MasterForwarder@updatefwd')->name('masterfwd_update');
        Route::get('/deletefwd/{id}', 'MasterForwarder@destroyfwd')->name('masterfwd_delete');
    });

    Route::group(['prefix' => 'hscode'], function () {
        Route::get('/', 'MasterHscode@index')->name('masterhscode');
        Route::get('/listhscode', 'MasterHscode@listhscode')->name('list_hscode');
        Route::post('/hscodeadd', 'MasterHscode@add')->name('masterhscode_add');
        Route::post('/hscodeedit', 'MasterHscode@edit')->name('masterhscode_edit');
        Route::post('/updatehscode', 'MasterHscode@update')->name('masterhscode_update');
        Route::get('/deletehscode/{id}', 'MasterHscode@destroy')->name('masterhscode_delete');
    });

    Route::group(['prefix' => 'route'], function () {
        Route::get('/', 'MasterRoute@index')->name('masterroute');
        Route::get('/listroute', 'MasterRoute@listroute')->name('list_route');
        Route::post('/routeadd', 'MasterRoute@add')->name('masterroute_add');
        Route::post('/routeedit', 'MasterRoute@edit')->name('masterroute_edit');
        Route::post('/updateroute', 'MasterRoute@update')->name('masterroute_update');
        Route::get('/deleteroute/{id}', 'MasterRoute@destroy')->name('masterroute_delete');
    });

    Route::group(['prefix' => 'pol'], function () {
        Route::get('/', 'MasterPOL@index')->name('masterpol');
        Route::get('/listpol', 'MasterPOL@listpol')->name('list_pol');
        Route::post('/poladd', 'MasterPOL@add')->name('masterpol_add');
        Route::post('/poledit', 'MasterPOL@edit')->name('masterpol_edit');
        Route::post('/updatepol', 'MasterPOL@update')->name('masterpol_update');
        Route::get('/deletepol/{id}', 'MasterPOL@destroy')->name('masterpol_delete');
    });
});
