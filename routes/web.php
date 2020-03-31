<?php

Route::redirect('/', '/login');

Route::redirect('/home', '/admin');

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::resource('products', 'ProductsController');
    
    Route::delete('schools/destroy', 'SchoolsController@massDestroy')->name('schools.massDestroy');
    Route::resource('schools', 'SchoolsController');
    Route::get('schools/assign-teacher/{id}',['as'=>'schools.assignTeachers','uses'=>'SchoolsController@assign']);
    Route::post('schools/assign-teacher',['as'=>'schools.assignTeachersPost','uses'=>'SchoolsController@assignTeacher']);
    
    Route::delete('students/destroy', 'StudentController@massDestroy')->name('students.massDestroy');
    Route::resource('students', 'StudentController');

    Route::delete('teachers/destroy', 'TeacherController@massDestroy')->name('teachers.massDestroy');
    Route::resource('teachers', 'TeacherController');

    Route::get('exams/lessons-videos/{courseId}/{testId?}',['as'=>'exams.lessonVideos','uses'=>'ExamController@lessonVideos']);
    Route::get('exams/history',['as'=>'exams.history','uses'=>'ExamController@getHistory']);
    Route::get('exams/exam-result/{testId?}',['as'=>'exams.examresult','uses'=>'ExamController@examResults']);
    Route::resource('exams', 'ExamController');
    Route::get('exams/take-exam/{courseId}/{lessonId?}',['as'=>'exams.takeexam','uses'=>'ExamController@takeExam']);
    Route::post('exams/submit-exams',['as'=>'exams.submitExam','uses'=>'ExamController@submitExam']);
    
    
});
