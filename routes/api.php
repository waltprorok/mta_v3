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

Route::group(['middleware' => 'api'], function () {

    Route::prefix('contact')->group(function () {
        Route::get('/', 'Api\ContactController@index');
        Route::get('/{contact}', 'Api\ContactController@show');
        Route::post('/store', 'Api\ContactController@store');
        Route::patch('/{contact}', 'Api\ContactController@update');
        Route::delete('/{contact}', 'Api\ContactController@delete');
    });
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
