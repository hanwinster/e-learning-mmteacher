<?php
// Member Routes
Route::namespace('API\Member')
    // ->middleware(['auth:api',
    //     'isApproved',
    //     'isVerified',
        // 'isBlocked'
        // 'isAdmin', 'isBlocked'
    //])
    ->middleware(['auth.api'])
    ->prefix('member')
    ->name('api.member.')

    ->group(function () {
        Route::post('course/{courseId}/rating-reviews/submit', 'RatingReviewController@saveRatingReview');
        Route::get('course/{courseId}/rating-reviews', 'RatingReviewController@index');

        Route::get('dashboard', 'DashboardController@index'); 

        Route::get('notification', 'NotificationController@index');
        Route::get('notification/{id}/mark-as-read', 'NotificationController@updateStatus');

        Route::get('course/{courseId}/discussion-messages', 'DiscussionController@index'); 
        Route::post('course/{courseId}/discussion-messages/{roomId}/add_message', 'DiscussionController@addMessage');

       // Route::get('courses/all', 'CourseController@index');
        Route::get('courses/my-courses', 'CourseController@myCourses');
      //  Route::get('courses/{id}', 'CourseController@show'); 
        Route::post('course/{courseId}/take-course', 'CourseController@takeCourse');
        Route::get('course/{courseId}/is-taken-course', 'CourseController@checkIfCourseTaken');
        Route::post('course/{courseId}/cancel-course', 'CourseController@cancelCourse');
        Route::get('course/{courseId}/get-course-intro', 'CourseController@viewCourseIntro'); 
        Route::get('course/{courseId}/get-download-course-resource-link', 'CourseController@downloadCourse');  
        Route::post('course/{courseId}/update-completion', 'CourseController@updateCompletion'); 
        Route::get('courses/{courseId}/is-ready-to-generate-certi', 'CourseController@isReadyToGenerateCerti'); 
        Route::get('courses/{courseId}/generate-certi', 'CourseController@generateCertificate'); 
        Route::get('courses/{courseId}/is-ready-to-evaluate', 'CourseController@isReadyToEvaluate');  
        Route::get('courses/{courseId}/is-ready-to-assess', 'CourseController@isReadyToAssess');  
        Route::get('courses/{courseId}/is_this_section_last/{section}', 'CourseController@isThisSectionLast'); 
        Route::get('courses/{courseId}/get_title_from_section/{section}', 'CourseController@getTitleFromValue');

        Route::get('course/{courseId}/lectures/{lectureId}', 'LectureController@getLectureContents');  
        Route::get('course/{courseId}/lectures/{lectureId}/get-download-lecture-resource-link', 'LectureController@downloadLecture');

        Route::get('course/{courseId}/quizzes/{quizId}', 'QuizController@getQuizContents'); 
        Route::get('course/{courseId}/quizzes/{quizId}/check-answers', 'QuizController@checkAnswer'); 
        Route::post('course/{courseId}/quizzes/{quizId}/submit-assignment', 'QuizController@submitAssignment');   
        Route::post('course/{courseId}/quizzes/{quizId}/submit-long-answer', 'QuizController@submitLongAnswer');   

        Route::get('course/{courseId}/learning-activities/{laId}', 'LearningActivityController@getLAContents'); 

        Route::get('course/{courseId}/summaries/{summaryId}', 'SummaryController@getSummaryContents'); 

        Route::get('course/{courseId}/live-sessions/{sessionId}', 'LiveSessionController@getLiveContents'); 
        Route::post('course/{courseId}/live-sessions/{sessionId}/register', 'LiveSessionController@registerSession'); 

        Route::get('course/{courseId}/evaluations', 'EvaluationController@getEvaluations'); 
        Route::post('course/{courseId}/evaluations/save', 'EvaluationController@saveEvaluations'); 
        Route::put('course/{courseId}/evaluations/{evaId}/update', 'EvaluationController@updateEvaluations'); 
        Route::post('course/{courseId}/evaluations/submit', 'EvaluationController@submitEvaluations'); 

        Route::get('course/{courseId}/assessments', 'AssessmentController@getAssessments'); 
        Route::get('course/{courseId}/assessments/{assessQId}/get-saved-data', 'AssessmentController@getAssessmentAndSavedDataById');
        Route::post('course/{courseId}/assessments/{assessQId}/save', 'AssessmentController@saveAssessment');
        Route::put('course/{courseId}/assessments/{assessQId}/update', 'AssessmentController@updateAssessment');  
        Route::post('course/{courseId}/assessments/submit', 'AssessmentController@submitAssessments');  

        //Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
        //Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');

        
        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user-token/valid', 'UserController@checkToken');
        Route::post('user/deactivate', 'UserController@deactivate');
        Route::post('profile', 'ProfileController@update')->name('profile.update');
        Route::post('change-password', 'ProfileController@updatePassword')->name('change-password.update');

    });
