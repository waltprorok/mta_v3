<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
| Routes accessible to authenticated, active, subscribed teachers.
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'teacher', 'subscribed', 'active']], function () {

    // Calendar & Dashboard
    Route::get('calendar', 'LessonController@index')->name('calendar.index');
    Route::view('dashboard', 'webapp.index')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Teacher Web API Endpoints
    |--------------------------------------------------------------------------
    */
    Route::prefix('web')->group(function () {
        // Student Lists
        Route::get('active', 'StudentListController@active')->name('student.active');
        Route::get('leads', 'StudentListController@leads')->name('student.leads');
        Route::get('waitlist', 'StudentListController@waitlist')->name('student.waitlist');
        Route::get('inactive', 'StudentListController@inactive')->name('student.inactive');
        // Billing Rates
        Route::resource('billing-rate', 'BillingRateController');
        // Dashboard
        Route::get('dashboard', 'DashboardController@dashboard');
        Route::get('dashboard/completed-lessons', 'DashboardController@getCompletedLessonsData');
        // Instruments
        Route::resource('instrument', 'InstrumentController');
        // Invoices
        Route::get('invoice', 'InvoiceController@index');
        Route::get('invoice-create/{month?}', 'InvoiceController@createInvoice');
        Route::get('invoice/list-of-payments', 'InvoiceController@getListOfPayments');
        Route::post('invoice-post', 'InvoiceController@store');
        Route::put('invoice/update/{invoice}', 'InvoiceController@update');
        Route::get('invoice-get-student/{id}/{month}', 'InvoiceController@getStudentSelected');
        // Holidays
        Route::resource('holiday', 'HolidayController');
        Route::resource('holidays', 'HolidaysController')->only(['index', 'store', 'destroy']);
        // Students
        Route::post('student-save', 'StudentController@store');
        // Reports
        Route::get('status', 'ReportController@status');
        Route::get('invoice/payments', 'ReportController@payments');
        // Payment Types
        Route::get('payment-types', 'PaymentTypeController@index');
        // Teacher Settings
        Route::resource('teacher-settings', 'TeacherSettingsController');
        // Student Lesson Scheduling & Rescheduling
        Route::get('student/lesson/{id}/{day?}', 'StudentLessonController@getStudent');
        Route::post('student/lessons', 'StudentLessonController@store');
        Route::get('lesson/reschedule/{id}/{day?}', 'StudentLessonController@getLessonReschedule');
        Route::patch('lesson/reschedule/update', 'StudentLessonController@update');
        Route::delete('lesson/delete/{id}', 'StudentLessonController@destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Invoice Pages
    |--------------------------------------------------------------------------
    */
    Route::prefix('invoice')->group(function () {
        // Invoice List
        Route::view('/', 'webapp.invoice.index')->name('invoice.index');
        // Invoice Details
        Route::get('/show/{invoice:id}', 'InvoiceController@show')->name('invoice.show');
        // Create Invoice
        Route::view('/create', 'webapp.invoice.create')->name('invoice.create');
        // Payments
        Route::view('/list-of-payments', 'webapp.invoice.list_of_payments')->name('invoice.list_of_payments');
        // PDF Export
        Route::get('/download/pdf/{invoice:id}', 'InvoiceController@downloadPDF')->name('invoice.download.pdf');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->group(function () {
        // Student List
        Route::view('/', 'webapp.student.index')->name('student.index');
        // Lesson Scheduling & Rescheduling
        Route::get('schedule/{id}', 'StudentLessonController@index')->name('student.schedule');
        Route::get('reschedule/{id}', 'StudentLessonController@show')->name('student.reschedule');
        // Student Profile
        Route::get('profile/{id}', 'StudentController@index')->name('student.profile');
        // Student Updates
        Route::post('update', 'StudentController@update')->name('student.update');
        Route::get('{id}/edit', 'StudentController@show')->name('student.edit');
        // Save Student Lessons
        Route::post('schedule/add', 'StudentLessonController@store')->name('student.schedule.save');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Studio & Settings
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher')->name('teacher.')->group(function () {
        // Studio
        Route::get('studio', 'TeacherController@index')->name('studio.index');
        Route::post('studio', 'TeacherController@store')->name('studio.store');
        Route::put('studio', 'TeacherController@update')->name('studio.update');
        // Profile
        Route::get('profile', 'TeacherController@profile')->name('profile');
        // Settings
        Route::view('rates', 'webapp.teacher.billing')->name('billing');
        Route::view('settings', 'webapp.teacher.settings')->name('settings');
        Route::view('holidays', 'webapp.teacher.holiday')->name('holidays');
        Route::view('instruments', 'webapp.teacher.instrument')->name('instruments');
        // Business Hours
        Route::get('hours', 'BusinessHourController@index')->name('hours');
        Route::get('hours/data', 'BusinessHourController@getHours')->name('hours.data');
        Route::put('hours', 'BusinessHourController@update')->name('hours.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->group(function () {
        // Student Status
        Route::view('status', 'webapp.reports.index')->name('reports.index');
        // Payments
        Route::view('payments', 'webapp.reports.payments')->name('reports.payments');
    });
});
