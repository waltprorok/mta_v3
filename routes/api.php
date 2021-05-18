<?php

use App\Contact;
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

Route::group(['middleware' => 'api'], function () {
    // Fetch contact us
    Route::get('contacts', function () {
        return Contact::latest()->orderBy('created_at', 'desc')->get();
    });

    // Delete Contact
    Route::delete('contact/{id}', function ($id) {
        return Contact::destroy($id);
    });


});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
