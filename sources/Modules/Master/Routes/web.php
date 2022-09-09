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
});
