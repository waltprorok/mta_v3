<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
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
