<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apitokenguestbook/{nik}', 'ApiServices@apitokenguestbook');
Route::get('/listguestbook/{key}/{nik}', 'ApiServices@listguestbook');
Route::post('/createguestbook/{key}', 'ApiServices@createguestbook');
Route::get('/listfactory/{key}', 'ApiServices@listfactory');
Route::get('/getguestbook/{key}/{id}', 'ApiServices@getguestbook');
Route::put('/updateguestbook/{key}/{id}', 'ApiServices@updateguestbook');
Route::delete('/deleteguestbook/{key}/{id}', 'ApiServices@deleteguestbook');
Route::get('/getdetailguestbook/{key}/{id}', 'ApiServices@getdetailguestbook');
