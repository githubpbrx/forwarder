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

Route::prefix('report')->group(function () {
    Route::get('/', 'ReportController@index');

    Route::group(['prefix' => 'po'], function () {
        Route::get('/', 'ReportPo@index')->name('reportpo');
        Route::post('/getpo/', 'ReportPo@getpo')->name('report_getpo');
        Route::post('search', 'ReportPo@datatable');
        Route::post('/getdetailpo/', 'ReportPo@detailpo')->name('report_detailpo');
        Route::get('/getexcelpo/{id}', 'ReportPo@excelpo')->name('report_excelpo');
        Route::get('/getexcelpoall/', 'ReportPo@excelpoall')->name('report_excelpoall');
    });

    Route::group(['prefix' => 'alokasi'], function () {
        Route::get('/', 'ReportAlokasi@index')->name('reportalokasi');
        Route::post('/getpo/', 'ReportAlokasi@getpo')->name('report_getpoalokasi');
        Route::post('/getfwd/', 'ReportAlokasi@getfwd')->name('report_getfwdalokasi');
        Route::post('search', 'ReportAlokasi@datatable');
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
        // Route::post('/getdetailforwarder/', 'HistoryAllocation@detailforwarder')->name('report_detailforwarder');
        // Route::get('/getexcelforwarder/{id}', 'HistoryAllocation@excelforwarder')->name('report_excelforwarder');
        // Route::get('/getexcelforwarderall/', 'HistoryAllocation@excelforwarderall')->name('report_excelforwarderall');
    });

    Route::group(['prefix' => 'shipment'], function () {
        Route::get('/', 'ReportShipment@index')->name('reportreadyshipment');
        Route::post('/getpo/', 'ReportShipment@getpo')->name('report_getposhipment');
        Route::post('search', 'ReportShipment@datatable');
        Route::post('/getdetailshipment/', 'ReportShipment@detailshipment')->name('report_detailshipment');
        Route::get('/getexcelshipment/{id}', 'ReportShipment@excelshipment')->name('report_excelshipment');
        Route::get('/getexcelshipmentall/', 'ReportShipment@excelshipmentall')->name('report_excelshipmentall');
    });


    Route::group(['prefix' => 'bestratefcl'], function () {
        Route::get('/', 'BestRateFcl@index')->name('bestratefcl');
        Route::get('/listresult', 'BestRateFcl@listresult')->name('list_bestratefcl');
        Route::get('/getexcel', 'BestRateFcl@getexcel')->name('getexcel');
    });

    Route::group(['prefix' => 'resultratefcladmin'], function () {
        Route::get('/', 'ResultRateAdmin@index')->name('resultratefcladmin');
        Route::get('/listresult', 'ResultRateAdmin@listresult')->name('list_resultratefcladmin');
        Route::post('/getreport', 'ResultRateAdmin@getreport')->name('getreport');
        Route::get('/getexcel/', 'ResultRateAdmin@getexcel')->name('getexcel');
    });

    Route::group(['prefix' => 'bestratefcladmin'], function () {
        Route::get('/', 'BestRateFcl@index')->name('bestratefcladmin');
        Route::get('/listresult', 'BestRateFcl@listresult')->name('list_bestratefcladmin');
        Route::post('/getbestrate', 'BestRateFcl@getbestrate')->name('getbestrate');
    });
});
