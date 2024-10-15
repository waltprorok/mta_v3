<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'admin']], function () {
    // web API endpoints for admin
    Route::prefix('web')->group(function () {
        Route::get('blogs', 'BlogController@list');
        Route::post('blog/', 'BlogController@store');
        Route::delete('blog/{id}', 'BlogController@destroy');
        Route::resource('contacts', 'ContactController');
        Route::get('students', 'StudentListController@adminStudents');
        Route::get('teachers', 'TeacherController@adminTeachers');
        Route::get('users', 'UserController@adminUsers');
        Route::get('billing/plans', 'BillingRateController@billingPlans');
    });

    Route::prefix('admin')->group(function () {
        Route::view('billing', 'webapp.admin.billing.plan')->name('admin.billing.plan.list');
        Route::view('blog', 'webapp.admin.blog.index')->name('admin.blog.list');
        Route::get('blog/{id}/edit', 'BlogController@edit')->name('admin.blog.edit');
        Route::view('blog/create', 'webapp.admin.blog.create')->name('admin.blog.create');
        Route::put('blog/{id}', 'BlogController@update')->name('admin.blog.update');
        Route::post('blog/', 'BlogController@store')->name('admin.blog.save');
        Route::view('contacts', 'webapp.admin.contact.index')->name('contact.index');
        Route::view('students', 'webapp.admin.student.index')->name('admin.student.index');
        Route::view('teachers', 'webapp.admin.teacher.index')->name('teacher.index');
        Route::view('users', 'webapp.admin.user.index')->name('admin.users');
    });
});

