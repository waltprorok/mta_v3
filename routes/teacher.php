<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'teacher']], function () {
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
    // web API endpoints for teacher
    Route::prefix('web')->group(function () {
        Route::get('active', 'StudentListController@active')->name('student.active');
        Route::get('leads', 'StudentListController@leads')->name('student.leads');
        Route::get('waitlist', 'StudentListController@waitlist')->name('student.waitlist');
        Route::get('inactive', 'StudentListController@inactive')->name('student.inactive');
        Route::resource('billing-rate', 'BillingRateController');
        Route::post('student-save', 'StudentController@store');
        Route::get('status', 'ReportController@status');
        Route::get('invoice', 'InvoiceController@index');
        Route::get('invoice-create', 'InvoiceController@createInvoice');
        Route::post('invoice-post', 'InvoiceController@store');
        Route::get('invoice-get-student/{id}', 'InvoiceController@getStudentSelected');
    });

    Route::prefix('invoice')->group(function () {
        Route::view('/', 'webapp.invoice.index')->name('invoice.index');
        Route::get('/show/{id}', 'InvoiceController@show')->name('invoice.show');
        Route::view('/create', 'webapp.invoice.create')->name('invoice.create');
        Route::get('/download/pdf/{id}', 'InvoiceController@downloadPDF')->name('invoice.download.pdf');
    });

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
        Route::view('status', 'webapp.reports.index')->name('reports.index');
    });
});
