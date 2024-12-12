<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'teacher', 'subscribed']], function () {
    Route::view('dashboard', 'webapp.index')->name('dashboard');
    // web API endpoints for teacher
    Route::prefix('web')->group(function () {
        Route::get('active', 'StudentListController@active')->name('student.active');
        Route::get('leads', 'StudentListController@leads')->name('student.leads');
        Route::get('waitlist', 'StudentListController@waitlist')->name('student.waitlist');
        Route::get('inactive', 'StudentListController@inactive')->name('student.inactive');
        Route::resource('billing-rate', 'BillingRateController');
        Route::get('dashboard', 'DashboardController@dashboard');
        Route::get('dashboard/completed-lessons', 'DashboardController@getCompletedLessonsData');
        Route::resource('instrument', 'InstrumentController');
        Route::get('invoice', 'InvoiceController@index');
        Route::get('invoice-create/{month?}', 'InvoiceController@createInvoice');
        Route::get('invoice/list-of-payments', 'InvoiceController@getListOfPayments');
        Route::post('invoice-post', 'InvoiceController@store');
        Route::put('invoice/update/{invoice}', 'InvoiceController@update');
        Route::get('invoice-get-student/{id}/{month}', 'InvoiceController@getStudentSelected');
        Route::resource('holiday', 'HolidayController');
        Route::post('student-save', 'StudentController@store');
        Route::get('status', 'ReportController@status');
        Route::get('payment-types', 'InvoiceController@getPaymentTypes');
        Route::resource('teacher-settings', 'TeacherSettingsController');
        Route::get('student/lesson/{id}/{day?}', 'StudentLessonController@getStudent');
        Route::post('student/lessons', 'StudentLessonController@store');
        Route::get('lesson/reschedule/{id}/{day?}', 'StudentLessonController@getLessonReschedule');
        Route::patch('lesson/reschedule/update', 'StudentLessonController@update');
        Route::delete('lesson/delete/{id}', 'StudentLessonController@destroy');
    });

    Route::prefix('invoice')->group(function () {
        Route::view('/', 'webapp.invoice.index')->name('invoice.index');
        Route::get('/show/{invoice:id}', 'InvoiceController@show')->name('invoice.show');
        Route::view('/create', 'webapp.invoice.create')->name('invoice.create');
        Route::view('/list-of-payments', 'webapp.invoice.list_of_payments')->name('invoice.list_of_payments');
        Route::get('/download/pdf/{invoice:id}', 'InvoiceController@downloadPDF')->name('invoice.download.pdf');
    });

    Route::prefix('students')->group(function () {
        Route::view('/', 'webapp.student.index')->name('student.index');
        Route::get('schedule/{id}', 'StudentLessonController@index')->name('student.schedule');
        Route::get('reschedule/{id}', 'StudentLessonController@show')->name('student.reschedule');
        Route::get('profile/{id}', 'StudentController@index')->name('student.profile');
        Route::post('update', 'StudentController@update')->name('student.update');
        Route::get('{id}/edit', 'StudentController@show')->name('student.edit');
        Route::post('schedule/add', 'StudentLessonController@store')->name('student.schedule.save');
        Route::put('lessons/update', 'StudentLessonController@lessonsUpdate')->name('student.lessons.update');
    });

    Route::prefix('teacher')->group(function () {
        Route::get('studio', 'TeacherController@index')->name('teacher.studioIndex');
        Route::put('update', 'TeacherController@update')->name('teacher.studioUpdate');
        Route::post('store', 'TeacherController@store')->name('save.studioSettings');
        Route::get('profile', 'TeacherController@profile')->name('teacher.profile');
        Route::view('rates', 'webapp.teacher.billing')->name('teacher.billing');
        Route::view('settings', 'webapp.teacher.settings')->name('teacher.settings');
        Route::view('holidays', 'webapp.teacher.holiday')->name('teacher.holidays');
        Route::view('instruments', 'webapp.teacher.instrument')->name('teacher.instruments');
        Route::get('hours', 'BusinessHourController@index')->name('teacher.hours');
        Route::post('hours', 'BusinessHourController@store')->name('teacher.hoursSave');
        Route::put('hours/update', 'BusinessHourController@update')->name('teacher.hoursUpdate');
    });

    Route::prefix('reports')->group(function () {
        Route::view('status', 'webapp.reports.index')->name('reports.index');
    });
});
