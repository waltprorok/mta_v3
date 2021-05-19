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

    // Get Single Contact
    Route::get('contact/{id}', function ($id) {
        return Contact::findOrFail($id);
    });

    // Add Contact
    Route::post('contact/store', function (Request $request) {
        return Contact::create([
            'name' => $request->input(['name']),
            'email' => $request->input(['email']),
            'subject' => $request->input(['subject']),
            'message' => $request->input(['message']),
        ]);
    });


    // Update Contact
    Route::patch('contact/{id}', function(Request $request, $id) {
        Contact::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'email' => $request->input(['email']),
            'subject' => $request->input(['subject']),
            'message' => $request->input(['message']),
        ]);
    });

    // Delete Contact
    Route::delete('contact/{id}', function ($id) {
        return Contact::destroy($id);
    });


});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
