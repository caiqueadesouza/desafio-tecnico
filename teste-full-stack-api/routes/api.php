<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/teste', function (Request $request) {
    return response()->json([
        'message' => 'API funcionando!',
        'status'  => true,
    ]);
});

Route::post('login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh-token', 'AuthController@refreshToken');
});

Route::group(['prefix' => 'entitys', 'middleware' => 'jwt.auth'], function () {
    Route::get('/', 'EntityController@index');
    Route::post('/', 'EntityController@store');
    Route::get('/{id}', 'EntityController@show');
    Route::put('/{id}', 'EntityController@update');
    Route::delete('/{id}', 'EntityController@destroy');
});

Route::group(['prefix' => 'specialties', 'middleware' => 'jwt.auth'], function () {
    Route::get('/', 'SpecialtyController@index');
});

Route::group(['prefix' => 'regionais', 'middleware' => 'jwt.auth'], function () {
    Route::get('/', 'RegionalController@index');
});
