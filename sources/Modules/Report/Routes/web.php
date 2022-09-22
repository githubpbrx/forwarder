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

Route::prefix('report')->group(function () {
    Route::get('/', 'ReportController@index');

    Route::group(['prefix' => 'po'], function () {
        Route::get('/', 'ReportPo@index')->name('reportpo');
        Route::post('/getpo/', 'ReportPo@getpo')->name('report_getpo');
        Route::get('search', 'ReportPo@datatable');
        Route::post('/getdetailpo/', 'ReportPo@detailpo')->name('report_detailpo');
        Route::get('/getexcelpo/{id}', 'ReportPo@excelpo')->name('report_excelpo');
    });

    Route::group(['prefix' => 'alokasi'], function () {
        Route::get('/', 'ReportAlokasi@index')->name('reportalokasi');
        Route::post('/getpo/', 'ReportAlokasi@getpo')->name('report_getpoalokasi');
        Route::get('search', 'ReportAlokasi@datatable');
        Route::post('/getdetailalokasi/', 'ReportAlokasi@detailalokasi')->name('report_detailalokasi');
    });
});
