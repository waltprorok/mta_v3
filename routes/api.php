<?php

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

    Route::resource('/contact', 'Api\ContactController');
    Route::resource('/lesson', 'Api\LessonController')->only(['index', 'update']);

});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
