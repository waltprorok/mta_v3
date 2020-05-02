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

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');
Route::get('pricing', 'HomeController@pricing')->name('pricing');
Route::get('blog', 'HomeController@blog')->name('blog');
Route::get('contact', 'HomeController@contact')->name('contact');
Route::post('contact', 'HomeController@sendContact');
Route::get('privacy', 'HomeController@privacy')->name('privacy');
Route::get('faq', 'HomeController@faq')->name('faq');
Route::get('terms', 'HomeController@terms')->name('terms');
Route::post('newsletter', 'NewsletterController@store')->name('newsletter');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::prefix('account')->group(function () {
        Route::get('/', 'SubscriptionController@index')->name('account');
        Route::get('invoice', 'SubscriptionController@invoices')->name('subscription.invoices');
        Route::get('invoices/{invoice}', 'SubscriptionController@pdfDownload')->name('subscription.invoice');
        Route::get('subscribed', 'SubscriptionController@subscribed')->name('subscribed');
        Route::post('subscription', 'SubscriptionController@create')->name('subscription.create');
        Route::get('card', 'SubscriptionController@creditCard')->name('subscription.card');
        Route::post('update-card', 'SubscriptionController@updateCreditCard')->name('subscription.updateCard');
        Route::get('cancel', 'SubscriptionController@cancel')->name('subscription.cancel');
        Route::get('resume', 'SubscriptionController@resume')->name('subscription.resume');
    });

    Route::prefix('teacher')->group(function () {
        Route::get('/', 'TeacherController@index')->name('teacher.studioIndex');
        Route::get('edit', 'TeacherController@edit')->name('teacher.editSettings');
        Route::put('update', 'TeacherController@update')->name('teacher.studioUpdate');
        Route::post('store', 'TeacherController@store')->name('save.studioSettings');
        Route::get('password', 'TeacherController@showChangePasswordForm')->name('pw.teacher');
        Route::post('password-post', 'TeacherController@changePassword')->name('pw.studioPW');
        Route::get('payment', 'TeacherController@payment')->name('teacher.payment');
        Route::get('schedule', 'TeacherController@hours')->name('teacher.hours');
    });


    Route::prefix('students')->group(function () {
        Route::get('/', 'StudentController@index')->name('student.index');
        Route::get('/waitlist', 'StudentController@waitlist')->name('student.waitlist');
        Route::get('/leads', 'StudentController@leads')->name('student.leads');
        Route::get('/inactive', 'StudentController@inactive')->name('student.inactive');
        Route::post('/save', 'StudentController@store')->name('student.save');
        Route::post('/update', 'StudentController@update')->name('student.update');
        Route::get('/{id}/edit', 'StudentController@edit')->name('student.edit');
        Route::get('/{id}/schedule', 'StudentController@schedule')->name('student.schedule');
        Route::post('/schedule/add', 'StudentController@scheduleSave')->name('student.schedule.save');
        Route::put('/schedule/update', 'StudentController@scheduleUpdate')->name('student.schedule.update');
        Route::get('/schedule/{student_id}/edit/{id}', 'StudentController@scheduleEdit')->name('student.schedule.edit');
        Route::delete('/schedule/{id}', 'StudentController@destroy')->name('student.schedule.delete');
    });

    Route::prefix('calendar')->group(function () {
        Route::get('/', 'LessonController@index')->name('calendar.index');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', 'ReportController@allReports')->name('reports.all');
    });

    // middleware guard for just admin user in dashboard
    Route::group(['middleware' => ['blogAdmin']], function () {
        Route::prefix('admin')->group(function () {
            Route::get('/blog', 'BlogController@list')->name('admin.blog.list');
            Route::get('/blog/{id}/edit', 'BlogController@edit')->name('admin.blog.edit');
            Route::get('/blog/create', 'BlogController@create')->name('admin.blog.create');
            Route::put('/blog/{id}', 'BlogController@update')->name('admin.blog.update');
            Route::post('/blog/', 'BlogController@store')->name('admin.blog.save');
            Route::delete('/blog/{id}', 'BlogController@destroy')->name('admin.blog.destroy');
        });
    });

});

Route::prefix('blog')->group(function () {
    Route::get('/', 'BlogController@index')->name('blog.index');
    Route::get('/{blog}', 'BlogController@show')->name('blog.show');

});

// middleware guard for subscribed users
Route::group(['middleware' => ['subscribed']], function () {

});




