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
        Route::post('updateProfile', 'SubscriptionController@updateProfile')->name('account.updateProfile');
    });

    Route::prefix('calendar')->group(function () {
        Route::get('/', 'LessonController@index')->name('calendar.index');
        Route::get('student', 'StudentUserController@calendar')->name('student.calendar');
    });

    Route::prefix('lessons')->group(function () {
        Route::view('/', 'webapp.lessons.index')->name('complete.lessons');
        Route::get('list', 'LessonController@list');
        Route::patch('update/{lesson}', 'LessonController@update');
    });

    Route::prefix('messages')->group(function () {
        Route::get('inbox', 'MessagesController@index')->name('message.inbox');
        Route::get('create/{id?}/{subject?}/{new?}', 'MessagesController@create')->name('message.create');
        Route::post('send', 'MessagesController@send')->name('message.send');
        Route::get('sent', 'MessagesController@sent')->name('message.sent');
        Route::get('read/{id}', 'MessagesController@read')->name('message.read');
        Route::get('delete/{id}', 'MessagesController@delete')->name('message.delete');
        Route::get('deleted', 'MessagesController@deleted')->name('message.deleted');
        Route::get('return/{id}', 'MessagesController@return')->name('message.return');
    });

    Route::group(['middleware' => ['household']], function () {
        Route::get('household', 'ParentController@household')->name('parent.household');
        Route::get('household/calendar', 'ParentController@calendar')->name('parent.calendar');
    });

    Route::group(['middleware' => ['teacher']], function () {
        Route::prefix('web')->group(function () {
            Route::get('active', 'StudentListController@active')->name('student.active');
            Route::get('leads', 'StudentListController@leads')->name('student.leads');
            Route::get('waitlist', 'StudentListController@waitlist')->name('student.waitlist');
            Route::get('inactive', 'StudentListController@inactive')->name('student.inactive');
            Route::resource('billing-rate', 'BillingRateController');
            Route::post('student-save', 'StudentController@store');
        });

        Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

        Route::prefix('students')->group(function () {
            Route::view('/', 'webapp.student.index')->name('student.index');
            Route::get('{id}/profile', 'StudentController@index')->name('student.profile'); // TODO route name
            Route::view('profile/{id}', 'webapp.student.profile')->name('student.profiles'); // TODO check route name
            Route::post('update', 'StudentController@update')->name('student.update');
            Route::get('{id}/edit', 'StudentController@show')->name('student.edit');
            Route::get('{id}/schedule/{day?}', 'StudentLessonController@index')->name('student.schedule');
            Route::post('schedule/add', 'StudentLessonController@store')->name('student.schedule.save');
            Route::put('schedule/update', 'StudentLessonController@update')->name('student.schedule.update');
            Route::get('schedule/{student_id}/edit/{id}/{day?}', 'StudentLessonController@show')->name('student.schedule.edit');
            Route::delete('schedule/delete/{id}', 'StudentLessonController@destroy')->name('student.schedule.delete');
            Route::put('lessons/update', 'StudentLessonController@lessonsUpdate')->name('student.lessons.update');
            Route::post('process_date', 'StudentLessonController@ajaxTime');
        });

        Route::prefix('teacher')->group(function () {
            Route::get('studio', 'TeacherController@index')->name('teacher.studioIndex');
            Route::put('update', 'TeacherController@update')->name('teacher.studioUpdate');
            Route::post('store', 'TeacherController@store')->name('save.studioSettings');
            Route::get('profile', 'TeacherController@profile')->name('teacher.profile');
            Route::view('rates', 'webapp.teacher.billing')->name('teacher.billing');
            Route::get('hours', 'BusinessHourController@index')->name('teacher.hours');
            Route::post('hours', 'BusinessHourController@store')->name('teacher.hoursSave');
            Route::put('hours/update', 'BusinessHourController@update')->name('teacher.hoursUpdate');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/', 'ReportController@all')->name('reports.all');
        });
    });

    // middleware guard for just admin user in dashboard
    Route::group(['middleware' => ['admin']], function () {
        // web API endpoints for admin
        Route::prefix('web')->group(function () {
            Route::resource('contact', 'ContactController');
            Route::get('teacher', 'TeacherController@adminTeachers');
            Route::get('student', 'StudentListController@adminStudents');
            Route::get('user', 'UserController@adminUsers');
            Route::get('blog', 'BlogController@list');
            Route::delete('blog/{id}', 'BlogController@destroy');
        });

        Route::prefix('admin')->group(function () {
            Route::view('blog', 'webapp.admin.blog.index')->name('admin.blog.list');
            Route::get('blog/{id}/edit', 'BlogController@edit')->name('admin.blog.edit');
            Route::get('blog/create', 'BlogController@create')->name('admin.blog.create');
            Route::put('blog/{id}', 'BlogController@update')->name('admin.blog.update');
            Route::post('blog/', 'BlogController@store')->name('admin.blog.save');
            Route::view('contacts', 'webapp.admin.contact.index')->name('contact.index');
            Route::view('teachers', 'webapp.admin.teacher.index')->name('teacher.index');
            Route::view('students', 'webapp.admin.student.index')->name('admin.student.index');
            Route::view('users', 'webapp.admin.user.index')->name('admin.users');
        });
    });
});

// middleware guard for subscribed users
Route::group(['middleware' => ['subscribed']], function () {
// Example of single route with middleware
// Route::get('/', 'LessonController@index')->name('calendar.index')->middleware('subscribed');
});
