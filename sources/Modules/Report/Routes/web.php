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
        Route::get('/getexcelpoall/', 'ReportPo@excelpoall')->name('report_excelpoall');
    });

    Route::group(['prefix' => 'alokasi'], function () {
        Route::get('/', 'ReportAlokasi@index')->name('reportalokasi');
        Route::post('/getpo/', 'ReportAlokasi@getpo')->name('report_getpoalokasi');
        Route::get('search', 'ReportAlokasi@datatable');
        Route::post('/getdetailalokasi/', 'ReportAlokasi@detailalokasi')->name('report_detailalokasi');
        Route::get('/getexcelalokasi/{id}', 'ReportAlokasi@excelalokasi')->name('report_excelalokasi');
        Route::get('/getexcelalokasiall/', 'ReportAlokasi@excelalokasiall')->name('report_excelalokasiall');
    });

    Route::group(['prefix' => 'forwarder'], function () {
        Route::get('/', 'ReportForwarder@index')->name('reportforwarder');
        Route::post('/getpo/', 'ReportForwarder@getpo')->name('report_getpo');
        Route::get('search', 'ReportForwarder@datatable');
        Route::post('/getdetailforwarder/', 'ReportForwarder@detailforwarder')->name('report_detailforwarder');
        Route::get('/getexcelforwarder/{id}', 'ReportForwarder@excelforwarder')->name('report_excelforwarder');
        Route::get('/getexcelforwarderall/', 'ReportForwarder@excelforwarderall')->name('report_excelforwarderall');
    });

    Route::group(['prefix' => 'allocation'], function () {
        Route::get('/', 'HistoryAllocation@index')->name('historyallocation');
        Route::get('/datatables', 'HistoryAllocation@datatable');
        Route::post('/getpo/', 'HistoryAllocation@getpo')->name('report_getpo');
        Route::post('/getdetailforwarder/', 'HistoryAllocation@detailforwarder')->name('report_detailforwarder');
        Route::get('/getexcelforwarder/{id}', 'HistoryAllocation@excelforwarder')->name('report_excelforwarder');
        Route::get('/getexcelforwarderall/', 'HistoryAllocation@excelforwarderall')->name('report_excelforwarderall');
    });
});
