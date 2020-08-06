<?php

// Route::redirect('/', '/login');

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('/how-it-works', 'Frontend\HomeController@howItWorks')->name('frontend.howitworks');
Route::get('/contact-us', 'Frontend\HomeController@contactUs')->name('frontend.contactus');
Route::post('/post-message',['as'=>'contact-us','uses'=>'Frontend\HomeController@postMessage']);

// Route::redirect('/home', '/admin/exams');

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['web','auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::get('users/activate/{id}', 'UsersController@activate')->name('users.activate');
    Route::resource('users', 'UsersController');
    // Route::get('users/activate-user/{id}',['as'=>'admin.users.activateUser','uses'=>'UsersController@activateUser']);

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
    Route::post('teachers/assign-school-to-teacher',['as'=>'teachers.assignSchool','uses'=>'TeacherController@assignSchool']);
    Route::get('teachers/assign-school/{id}',['as'=>'teachers.assign-school','uses'=>'TeacherController@assign']);
    

    Route::get('exams/lessons-videos/{courseId}/{testId?}',['as'=>'exams.lessonVideos','uses'=>'ExamController@lessonVideos']);
    Route::get('exams/history',['as'=>'exams.history','uses'=>'ExamController@getHistory']);
    Route::get('exams/exam-result/{testId?}',['as'=>'exams.examresult','uses'=>'ExamController@examResults']);
    Route::resource('exams', 'ExamController');
    Route::get('exams/take-exam/{courseId?}/{lessonId?}/{testId?}',['as'=>'exams.takeexam','uses'=>'ExamController@takeExam']);
    Route::post('exams/submit-exams',['as'=>'exams.submitExam','uses'=>'ExamController@submitExam']);
    Route::get('exams/delete-test/{testId}',['as'=>'exams.deleteTest','uses'=>'ExamController@deleteTest']);
    
    
});
