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

// Routes for fronted marketing
Route::get('/', 'HomeController@index')->name('home');
Route::get('pricing', 'HomeController@pricing')->name('pricing');
Route::get('blog', 'HomeController@blog')->name('blog');
Route::get('contact', 'HomeController@contact')->name('contact');
Route::post('contact', 'HomeController@sendContact');
Route::get('privacy', 'HomeController@privacy')->name('privacy');
Route::get('faq', 'HomeController@faq')->name('faq');
Route::get('terms', 'HomeController@terms')->name('terms');
Route::post('newsletter', 'NewsletterController@store')->name('newsletter');

// Routes for frontend blog
Route::prefix('blog')->group(function () {
    Route::get('/', 'BlogController@index')->name('blog.index');
    Route::get('/{blog}', 'BlogController@show')->name('blog.show');

});

// Routes for authorized users
Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

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
        Route::get('profile', 'SubscriptionController@profile')->name('account.profile');
        Route::post('updateProfile', 'SubscriptionController@updateProfile')->name('account.updateProfile');
    });

    Route::prefix('teacher')->group(function () {
        Route::get('/', 'TeacherController@index')->name('teacher.studioIndex');
        Route::get('edit', 'TeacherController@edit')->name('teacher.editSettings');
        Route::put('update', 'TeacherController@update')->name('teacher.studioUpdate');
        Route::post('store', 'TeacherController@store')->name('save.studioSettings');
        Route::get('/profile', 'TeacherController@profile')->name('teacher.profile');
        Route::get('payment', 'TeacherController@payment')->name('teacher.payment');
        Route::get('hours', 'TeacherController@hours')->name('teacher.hours');
        Route::post('hours', 'TeacherController@hoursSave')->name('teacher.hoursSave');
        Route::get('hours/view', 'TeacherController@hoursView')->name('teacher.hoursView');
        Route::put('hours/update', 'TeacherController@hoursUpdate')->name('teacher.hoursUpdate');
    });


    Route::prefix('students')->group(function () {
        Route::get('/', 'StudentController@index')->name('student.index');
        Route::get('/{id}/profile', 'StudentController@profile')->name('student.profile');
        Route::get('/waitlist', 'StudentController@waitlist')->name('student.waitlist');
        Route::get('/leads', 'StudentController@leads')->name('student.leads');
        Route::get('/inactive', 'StudentController@inactive')->name('student.inactive');
        Route::post('/save', 'StudentController@store')->name('student.save');
        Route::post('/update', 'StudentController@update')->name('student.update');
        Route::get('/{id}/edit', 'StudentController@edit')->name('student.edit');
        Route::get('/{id}/schedule/{day?}', 'StudentController@schedule')->name('student.schedule');
        Route::post('/schedule/add', 'StudentController@scheduleSave')->name('student.schedule.save');
        Route::put('/schedule/update', 'StudentController@scheduleUpdateStore')->name('student.schedule.update');
        Route::get('/schedule/{student_id}/edit/{id}/{day?}', 'StudentController@scheduleEdit')->name('student.schedule.edit');
        Route::delete('/schedule/{id}', 'StudentController@scheduledLessonDelete')->name('student.schedule.delete');
        Route::delete('/schedule/delete/{id}', 'StudentController@scheduledLessonDelete')->name('student.schedule.deleteAll');
        Route::get('/lessons', 'StudentController@lessons')->name('student.lessons');
        Route::post('/process_date', 'StudentController@ajaxTime');
    });

    Route::prefix('admin')->group(function () {
       Route::get('teachers', 'TeacherController@adminTeachers')->name('webapp.admin.teachers');
       Route::get('students', 'StudentController@adminStudents')->name('webapp.admin.students');
       Route::get('users', 'UserController@adminUsers')->name('webapp.admin.users');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', 'ContactController@indexBlade')->name('webapp.contact.index');
    });

    Route::prefix('calendar')->group(function () {
        Route::get('/', 'LessonController@index')->name('calendar.index');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', 'ReportController@all')->name('reports.all');
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

// middleware guard for subscribed users
Route::group(['middleware' => ['subscribed']], function () {
// Example of single route with middleware
// Route::get('/', 'LessonController@index')->name('calendar.index')->middleware('subscribed');
});




