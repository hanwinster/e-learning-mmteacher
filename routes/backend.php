<?php

// Backend Routes
Route::namespace('Admin')
    ->middleware(['auth',
    'isAdmin', 'isApproved',
    // 'isBlocked'
    ])
    ->prefix(config('cms.backend_uri'))
    ->name('admin.')
    ->group(function () {
        Route::resource('contact', 'ContactController');
        Route::resource('block', 'BlockController');
        Route::resource('page', 'PageController');
        Route::resource('slide', 'SlideController');
        Route::resource('faq', 'FaqController');
        Route::resource('link-report', 'LinkReportController');
        Route::resource('faq-category', 'FaqCategoryController');
        Route::resource('subject', 'SubjectController');
        //Route::resource('resource_format', 'ResourceFormatController');
        Route::resource('keyword', 'KeywordController');
        Route::get('get/keywords', array('as' => 'get.keywords', 'uses' => 'KeywordController@getKeywords'));

        Route::resource('license', 'LicenseController');

        Route::resource('resource', 'ResourceController');
        Route::get('resources/batch-upload', 'ResourceController@importForm')->name('resource.batch-upload.create');
        Route::post('resources/batch-upload', 'ResourceController@index')->name('resource.batch-upload.store');
        Route::get('resource/create/{format}', 'ResourceController@create')->name('resource.create-with-format');

        Route::get('resource/{id}/related', 'RelatedResourcesController@show')
        ->name('resource.related');
        Route::post('resource/{id}/add-resource', 'RelatedResourcesController@store')
        ->name('resource.add-related');
        Route::get('resource/{id}/remove-resource/{resource_id}', 'RelatedResourcesController@destroy')
        ->name('resource.remove-related');

        Route::get('resource/{id}/new-version', 'ResourceController@clone')->name('resource.new-version');

        Route::resource('article', 'ArticleController');
        Route::resource('article-category', 'ArticleCategoryController');
        Route::resource('year', 'YearController');
        Route::resource('college', 'CollegeController');
        Route::resource('user', 'UserController');
        Route::get('list_approved_user', 'UserController@indexofApproved')->name('user.approved_user');
        Route::get('list_blocked_user', 'UserController@indexofBlocked')->name('user.blocked_user');
        Route::get('users/batch-upload', 'UserController@importForm')->name('user.batch-upload.create');
        Route::post('users/batch-upload', 'ExcelImportController@importUsers')->name('user.batch-upload.store');
        Route::resource('role', 'RoleController');
        Route::get('media/{id}/delete', 'MediaController@destroy')->name('media.destroy');
        Route::get('ajax-delete/{id}/media', 'MediaController@deleteMediaByResource')->name('ajax.media.destroy');

        Route::get('change-password', 'ProfileController@getChangePassword')->name('profile.change-password');
        Route::post('change-password', [
            'as' => 'profile.update-password',
            'uses' => 'ProfileController@postChangePassword'
        ]);

        Route::get('change-profile', 'ProfileController@getEditProfile')->name('profile.change-profile');
        Route::post('change-profile', [
            'as' => 'profile.update-profile',
            'uses' => 'ProfileController@postEditProfile'
        ]);

        Route::get('import/user', 'ImportUserController@create')->name('user.bulk-import');
        Route::post('import/user', 'ImportUserController@store')->name('user.save-bulk-import');

        Route::get('import/resource', 'ImportResourceController@create')->name('resource.bulk-import');
        Route::post('import/resource', 'ImportResourceController@store')->name('resource.save-bulk-import');
        Route::get('clean-up-notification-links', 'HousekeepingController@updateNotificationLinks')
            ->name('clean-up-notification-links');

        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::resource('course', 'CourseController');
        Route::get('take-course-user/{course_id}', 'CourseController@takeCourseUser')->name('take-course-user');
        Route::resource('course-category', 'CourseCategoryController');
        Route::resource('course-level', 'CourseLevelController');
        Route::resource('course-type', 'CourseTypeController');
        Route::resource('course-evaluation', 'CourseEvaluationController');

        // lecture
        Route::get('course-lecture/{course_id}/create', 'LectureController@create')->name('lecture.create');
        Route::post('course-lecture/{course_id}/create', 'LectureController@store')->name('lecture.store');
        Route::get('course-lecture/{lecture_id}/edit', 'LectureController@edit')->name('lecture.edit');
        Route::put('course-lecture/{lecture_id}/edit', 'LectureController@update')->name('lecture.update');
        Route::delete('course-lecture/{lecture_id}', 'LectureController@destroy')->name('lecture.destroy');

        // quiz
        Route::get('course-quiz/{course_id}/create', 'QuizController@create')->name('quiz.create');
        Route::post('course-quiz/{course_id}/create', 'QuizController@store')->name('quiz.store');
        Route::get('course-quiz/{quiz_id}/edit', 'QuizController@edit')->name('quiz.edit');
        Route::put('course-quiz/{quiz_id}/edit', 'QuizController@update')->name('quiz.update');
        Route::delete('course-quiz/{quiz_id}', 'QuizController@destroy')->name('quiz.destroy');

        // Assignment
        Route::get('course-assignment/{course_id}/create', 'AssignmentController@create')->name('assignment.create');
        Route::post('course-assignment/{course_id}/create', 'AssignmentController@store')->name('assignment.store');
        Route::get('course-assignment/{assignment_id}/edit', 'AssignmentController@edit')->name('assignment.edit');
        Route::put('course-assignment/{assignment_id}/edit', 'AssignmentController@update')->name('assignment.update');
        Route::delete('course-assignment/{assignment_id}', 'AssignmentController@destroy')->name('assignment.destroy');
        Route::get('assignment/{id}', 'AssignmentController@show')->name('assignment.show');
        Route::get('assignment/{id}/user-assignment', 'AssignmentController@userAssignment')->name('assignment.detail');
        Route::post('user-assignment/edit-comment', 'AssignmentReviewController@reviewAssignment')->name('assignment.review');

        Route::get('course-question/{quiz_id}/create', 'QuestionController@create')->name('question.create');
        Route::post('course-question/{quiz_id}/create', 'QuestionController@store')->name('question.store');
        Route::get('course-question/{question_id}/edit', 'QuestionController@edit')->name('question.edit');
        Route::put('course-question/{question_id}/edit', 'QuestionController@update')->name('question.update');
        Route::delete('course-question/{question_id}', 'QuestionController@destroy')->name('question.destroy');
    });
