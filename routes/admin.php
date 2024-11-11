<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'admin']], function () {
    // web API endpoints for admin
    Route::prefix('web')->group(function () {
        Route::get('blogs', 'BlogController@list');
        Route::post('blog', 'BlogController@store');
        Route::delete('blog/{id}', 'BlogController@destroy');
        Route::get('blog/{blog}/edit', 'BlogController@edit');
        Route::put('blog/{blog}', 'BlogController@update');
        Route::patch('blog/image/{blog}', 'BlogController@updateImage');
        Route::resource('contacts', 'ContactController');
        Route::put('reply-contact/{contact}', 'ContactController@updateReply');
        Route::get('students', 'StudentListController@adminStudents');
        Route::resource('support', 'SupportController');
        Route::patch('reply-support/{support}', 'SupportController@updateReply');
        Route::get('teachers', 'TeacherController@adminTeachers');
        Route::get('users', 'UserController@adminUsers');
        Route::get('billing/plans', 'BillingRateController@billingPlans');
    });

    Route::prefix('admin')->group(function () {
        Route::view('billing', 'webapp.admin.billing.plan')->name('admin.billing.plan.list');
        Route::view('blog', 'webapp.admin.blog.index')->name('admin.blog.list');
        Route::view('blog/create', 'webapp.admin.blog.create')->name('admin.blog.create');
        Route::view('blog/{id}/edit', 'webapp.admin.blog.edit')->name('admin.blog.edit');
        Route::view('contacts', 'webapp.admin.contact.index')->name('contact.index');
        Route::view('students', 'webapp.admin.student.index')->name('admin.student.index');
        Route::view('support', 'webapp.admin.support.index')->name('admin.support.index');
        Route::view('teachers', 'webapp.admin.teacher.index')->name('teacher.index');
        Route::view('users', 'webapp.admin.user.index')->name('admin.users');
    });
});

