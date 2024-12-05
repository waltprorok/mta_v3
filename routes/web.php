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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::middleware(ProtectAgainstSpam::class)->group(function () {
    Auth::routes();
});

// Routes for marketing
Route::get('/', 'HomeController@index')->name('home');
Route::get('pricing', 'HomeController@pricing')->name('pricing');
Route::get('contact', 'HomeController@contact')->name('contact');
Route::post('contact', 'HomeController@createContact')->middleware(ProtectAgainstSpam::class);
Route::get('privacy', 'HomeController@privacy')->name('privacy');
Route::get('faq', 'HomeController@faq')->name('faq');
Route::get('terms', 'HomeController@terms')->name('terms');
Route::post('newsletter', 'NewsletterController@store')->name('newsletter')->middleware(ProtectAgainstSpam::class);

// Routes for blog
Route::prefix('blog')->group(function () {
    Route::get('/', 'BlogController@index')->name('blog.index');
    Route::get('{blog}', 'BlogController@show')->name('blog.show');
});

// Routes for authorized users
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('account')->group(function () {
        Route::get('subscription', 'SubscriptionController@index')->name('account.subscription');
        Route::get('subscription/invoices', 'SubscriptionController@invoices')->name('subscription.invoices');
        Route::get('subscription/invoices/{invoice}', 'SubscriptionController@pdfDownload')->name('subscription.invoice');
        Route::get('subscription/subscribed', 'SubscriptionController@subscribed')->name('subscribed');
        Route::post('subscription/subscription', 'SubscriptionController@create')->name('subscription.create');
        Route::get('subscription/update-card', 'SubscriptionController@creditCard')->name('subscription.card');
        Route::post('subscription/updated-card', 'SubscriptionController@updateCreditCard')->name('subscription.updateCard');
        Route::get('subscription/cancel', 'SubscriptionController@cancel')->name('subscription.cancel');
        Route::get('subscription/resume', 'SubscriptionController@resume')->name('subscription.resume');
        Route::get('subscription/change-plan', 'SubscriptionController@listPlanChange')->name('subscription.change');
        Route::post('subscription/changed', 'SubscriptionController@changePlan')->name('subscription.changed');
        Route::get('profile', 'SubscriptionController@profile')->name('account.profile');
        Route::post('update-profile', 'SubscriptionController@updateProfile')->name('account.updateProfile');
    });

    Route::prefix('calendar')->group(function () {
        Route::get('/', 'LessonController@index')->name('calendar.index');
        Route::get('student', 'StudentUserController@calendar')->name('student.calendar');
        Route::view('lesson/get/{id}', 'webapp.lesson.cancel')->name('lesson.cancel');
    });

    Route::prefix('lessons')->group(function () {
        Route::view('/', 'webapp.lessons.index')->name('complete.lessons');
        Route::get('list/{fromDate}/{toDate}', 'LessonController@list');
        Route::patch('update/{lesson}', 'LessonController@update');
    });

    Route::prefix('messages')->group(function () {
        Route::view('/', 'webapp.messages.index')->name('message.index');
    });

    Route::prefix('web')->group(function () {
        Route::get('messages/inbox', 'MessagesController@index');
        Route::get('messages/index/{id}', 'MessagesController@show');
        Route::get('messages/status/{status?}', 'MessagesController@status');
        Route::post('messages/store', 'MessagesController@store');
        Route::get('payments', 'PaymentController@index');
        Route::get('lesson/get/{id}', 'ParentController@getLesson');
        Route::patch('lesson/cancel', 'ParentController@cancelLesson');

    });
    Route::prefix('payments')->group(function () {
        Route::view('/', 'webapp.payments.payments')->name('payment.index');
        Route::get('/download/pdf/{id}', 'InvoiceController@downloadPDF')->name('payments.download.pdf');
        Route::get('/invoice/show/{id}', 'InvoiceController@show')->name('payments.show');
    });

    Route::get('support', 'SupportUserController@index')->name('support');
    Route::post('support', 'SupportUserController@store')->middleware(ProtectAgainstSpam::class);

    Route::group(['middleware' => ['household']], function () {
        Route::get('household', 'ParentController@household')->name('parent.household');
        Route::get('household/calendar', 'ParentController@calendar')->name('parent.calendar');
        Route::view('household/lesson/get/{id}', 'webapp.lesson.cancel')->name('household.lesson.cancel');
    });
});

// middleware guard for subscribed users
// Route::group(['middleware' => ['subscribed']], function () {
//// Example of single route with middleware
// Route::get('/', 'LessonController@index')->name('calendar.index')->middleware('subscribed');
// });
