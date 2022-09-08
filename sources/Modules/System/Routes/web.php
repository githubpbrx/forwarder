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

Route::get('/sycnsap', 'SyncSap@index')->name('syncSAP');
Route::get('/sycnsap249', 'SyncSap@index_syncsap')->name('SAPsync');

Route::get('/detail/{nik}', 'login@apiDetail');
Route::prefix('')->group(function () {
    Route::get('/dashboard', 'home@index')->name('dashcam');
    Route::get('/pagepo', 'home@pagepo')->name('page_po');
    Route::get('/pageupdate', 'home@pageupdate')->name('page_update');
    Route::get('/listpo', 'home@listpo')->name('list_po');
    Route::get('/listupdate', 'home@listupdate')->name('list_update');
    Route::post('/formpo', 'home@formpo')->name('form_po');
    Route::post('/formupdate', 'home@formupdate')->name('form_update');
    Route::post('/saveformpo', 'home@saveformpo')->name('formposave');
    Route::post('/saveshipment', 'home@saveshipment')->name('saveshipment');

    Route::get('/pageapproval', 'home@pageapproval')->name('page_approval');

    Route::get('/validasicoc', 'login@validasicoc')->name('validasicoc');
    Route::get('/validasikyc', 'login@validasikyc')->name('validasikyc');


    Route::get('/aktifasiuser', 'login@aktifasiuser')->name('aktifasiuser');
    Route::get('/resendemail', 'login@resendemail')->name('resendemail');

    Route::post('login/validasiaktifasi', 'login@validasiaktifasi')->name('validasiaktifasi');
    Route::get('getvalidation/{token}/{kode}/{posh}', 'login@getvalidasi')->name('getvalidasi');

    Route::get('/login', 'login@login');
    Route::get('/login/getkaryawan/{nik}', 'login@getKaryawan')->name('system_getkaryawan');
    Route::get('/login/pass_exp', 'login@exp_password')->name('exp_pass');
    Route::get('/login/pass_exp_action', 'login@exp_password_action')->name('exp_pass_action');
    Route::get('loginChance', 'login@loginChance');
    Route::get('/redirect', 'login@index');
    Route::post('/loginaction', 'login@loginaction');
    Route::get('/logout', 'login@logout');

    //forgot password
    Route::get('/forgotpassword', 'login@forgotpasswordstep1');
    Route::post('/forgotpassword', 'login@forgotpasswordstep2');
    Route::post('/forgotpasswordaction', 'login@forgotpasswordaction');

    //new password
    Route::get('/login/newpassword', 'login@newpassword');
    Route::post('/login/newpasswordaction', 'login@newpasswordaction');
    Route::get('/login/newnohripspassword', 'login@newnohripspassword');
    Route::post('/login/newnohripspasswordaction', 'login@newnohripspasswordaction');
    Route::get('/login/checkbirthday', 'login@checkbirthday');

    Route::get('/checknik', 'login@checknik');
    Route::get('/checklogin', 'login@checklogin');
    Route::get('car/booking/getdetailbynik/{nik}', 'login@getdetailbynik');

    Route::get('/choosemenu', 'login@choosemenu');
    Route::get('/choosemenu/{menu}', 'login@choosemenu');

    Route::get('/', 'login@index');



    // setting routes
    Route::get('changepassword', 'settings@changepassword');
    Route::post('changepasswordaction', 'settings@changepasswordaction');
    Route::get('/settings/application', 'settings@application');
    Route::get('/settings/applicationdata/{system_id}', 'settings@applicationdata');
    Route::post('/settings/applicationupdateaction', 'settings@applicationupdateaction');
    Route::get('/settings/log', 'log@index');
    Route::get('/settings/logactivities', 'log@log');
    Route::get('/settings/useraccess', 'settings@useraccess');
    Route::get('/settings/useraccess/{nik}', 'settings@useraccess');
    Route::post('/settings/useraccessaction', 'settings@useraccessaction');
    Route::get('/settings/systemdata/{group_access_id}', 'settings@systemdata');
    Route::get('/settings/sbu', 'sbu@index');
    Route::get('/settings/sbu/sbudata', 'sbu@sbudata');
    Route::post('/settings/sbu/createaction', 'sbu@createaction');
    Route::post('/settings/sbu/updateaction', 'sbu@updateaction');
    Route::get('/settings/sbu/delete/{sbu_id}', 'sbu@delete');



    #IMPORTAN SISTEM SETTINGS
    Route::get('/app/settings/panel', 'configuration@settingspanel');
    Route::post('/app/settings/panel', 'configuration@settingspanelaction');
    Route::get('/app/settings/panel/auth', 'configuration@authpanel');
    Route::post('/app/settings/panel/auth', 'configuration@authpanelaction');
    Route::get('/app/settings/panel/exit', 'configuration@exitpanel');
});

Route::prefix('privilege')->group(function () {
    Route::get('menu', 'Privileges\menu@index');
    Route::post('menu/createaction', 'Privileges\menu@createaction');
    Route::post('menu/updateaction', 'Privileges\menu@updateaction');
    Route::get('menu/delete/{menu_id}', 'Privileges\menu@delete');
    Route::get('menu/active/{menu_id}', 'Privileges\menu@active');

    Route::get('user_access', 'Privileges\privilege@index');
    Route::get('user_accessadd', 'Privileges\privilege@add')->name('user_add');
    Route::get('user_access/privilegedata', 'Privileges\privilege@privilegedata');
    Route::get('user_access/update/{privilege_id}', 'Privileges\privilege@update');
    Route::post('user_access/updateaction', 'Privileges\privilege@updateaction')->name('privilegeupdateaction');
    Route::get('user_access/resetpassword/{nik}', 'Privileges\privilege@resetpassword');
    Route::get('user_access/resetqa/{nik}', 'Privileges\privilege@resetqa');

    Route::get('group_access', 'Privileges\group_access@index');
    Route::get('group_access/create', 'Privileges\group_access@create');
    Route::post('group_access/createaction', 'Privileges\group_access@createaction');
    Route::get('group_access/update/{group_access_id}', 'Privileges\group_access@update');
    Route::post('group_access/updateaction', 'Privileges\group_access@updateaction');
    Route::get('group_access/delete/{group_access_id}', 'Privileges\group_access@delete');
    Route::get('group_access/checkgroupaccess', 'Privileges\group_access@checkgroupaccess');

    Route::get('get/{nik}', 'Privileges\privilege@getPrivilege');
    //

    Route::post('getnamaku', 'Privileges\privilege@getnama')->name('privileggetnama');

    Route::post('createsave', 'Privileges\privilege@createsave')->name('privilegcreatesave');
});
