<?php
// Member Routes

use App\Models\Certificate;

Route::namespace('Member')
    ->middleware(['auth',
        'isApproved',
        'isVerified',
        // 'isBlocked'
        // 'isAdmin', 'isBlocked'
    ])
    ->name('member.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::post('dashboard-notify', 'DashboardController@notify')->name('dashboard.notify-user');
        Route::post('dashboard-remove-user', 'DashboardController@remove')->name('dashboard.remove-user');  

        Route::get('my-favourite', 'FavouriteController@index')->name('favourite.index');

        Route::get('preview/{id}', 'PreviewController@show')->name('preview.show');
        Route::get('profile', 'ProfileController@edit')->name('profile.edit');
        Route::post('profile', 'ProfileController@update')->name('profile.update');

        Route::get('change-password', 'ProfileController@changePassword')->name('change-password.edit');
        Route::post('change-password', 'ProfileController@updatePassword')->name('change-password.update');

        Route::get('notifications', 'NotificationController@index')->name('notification.index');
        Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
        Route::get('notification/{id}/delete', 'NotificationController@destroy')->name('notification.destroy');
        Route::get('profile/view-user', 'ViewUserController@index')->name('view-user.index');

        Route::post('review/{courseId}', 'RatingReviewController@store')->name('rating-review.store');
        
        Route::middleware('isAdminOrManagerOrEducator')
        ->group(function () {
             
            // course
            //Route::resource('profile/course', 'CourseController')->middleware('canCRUDOnCourse');
            Route::middleware('canCRUDOnCourse')->group(function() {
                Route::get('profile/course', 'CourseController@index')->name('course.index');
                Route::get('profile/course/create', 'CourseController@create')->name('course.create');  
                Route::get('profile/course/{id}', 'CourseController@show')->name('course.show');                                
                Route::get('profile/course/{course_id}/edit', 'CourseController@edit')->name('course.edit');                                        
                Route::delete('profile/course/{course_id}', 'CourseController@destroy')->name('course.destroy');        
            });
            
            Route::post('profile/course/store', 'CourseController@store')->name('course.store');
            Route::put('profile/course/{course_id}/edit', 'CourseController@update')->name('course.update'); 
            Route::get('profile/course/{course_id}/view-evaluations', 'CourseController@showEvaluations')->name('course.view-evaluations');

            //Route::post('profile/course/store', 'CourseController@store')->name('course.store'); //->middleware('canCRUDOnCourse');    
            Route::get('profile/take-course-user/{id}', 'CourseController@takeCourseUser')->name('take-course-user');
            Route::get('profile/course/{id}/clone', 'CourseController@cloneCourse')->name('clone-course');
            Route::post('profile/take-course-user/notify', 'CourseController@notifyCourseTakers')->name('course.notify-course-takers');    

            Route::middleware('canCRUDOnLecture')->group(function(){
                // lecture
                Route::get('profile/course-lecture/{course_id}/create', 'LectureController@create')->name('lecture.create')->middleware('canCRUDOnCourse');
                //Route::post('profile/course-lecture/{course_id}/create', 'LectureController@store')->name('lecture.store')->middleware('canCRUDOnCourse');
                Route::get('profile/course-lecture/{lecture_id}/edit', 'LectureController@edit')->name('lecture.edit');
                //Route::put('profile/course-lecture/{lecture_id}/edit', 'LectureController@update')->name('lecture.update');
                Route::delete('profile/course-lecture/{lecture_id}', 'LectureController@destroy')->name('lecture.destroy');
                Route::post('profile/course-lecture/{course_id}/create', 'LectureController@store')->name('lecture.store');
                Route::put('profile/course-lecture/{lecture_id}/edit', 'LectureController@update')->name('lecture.update');
            });
                      
            //assessment setting
            Route::get('profile/course/{course_id}/assessment/edit', 'CourseController@editAssessment')->name('course.assessment.edit');
            Route::put('profile/course/{course_id}/assessment/edit', 'CourseController@updateAssessment')->name('course.assessment.update'); 
            //assessment question & answers
            Route::get('profile/course/{course_id}/assessment-qa/create', 'AssessnmentQAController@create')->name('course.assessment-qa.create');
            Route::post('profile/course/{course_id}/assessment-qa/create', 'AssessnmentQAController@store')->name('course.assessment-qa.store');
            Route::put('profile/course-assessment-qa/{id}/edit', 'AssessnmentQAController@update')->name('course.assessment-qa.update');
            Route::get('profile/course-assessment-qa/{id}/edit', 'AssessnmentQAController@edit')->name('course.assessment-qa.edit');
            Route::delete('profile/course-assessment-qa/{id}', 'AssessnmentQAController@destroy')->name('course.assessment-qa.destroy');
            Route::get('profile/course-assessment-qa/{id}/user-assessment', 'AssessnmentQAController@userAssessment')->name('course.assessment-qa.detail');
            Route::get('profile/course-assessment-qa/{courseId}/{id}/user-assessment-long-answer', 'AssessnmentQAController@userLongAnswer')->name('course.assess_long_answer.detail');
            Route::post('profile/course-assessment-qa/edit-user-assessment-long-answer', 'AssessnmentQAController@reviewAnswer')->name('course.ajax-assess-long-answer-review');
            //discussion
            Route::get('profile/course-discussion/{discussion_id}/edit', 'DiscussionController@edit')->name('course.discussion.edit');
            Route::put('profile/course-discussion/{discussion_id}/edit', 'DiscussionController@update')->name('course.discussion.update'); 
            Route::get('profile/course-discussion/{course_id}/create', 'DiscussionController@create')->name('course.discussion.create');
            Route::post('profile/course-discussion/{course_id}/create', 'DiscussionController@store')->name('course.discussion.store');

            //certificate
            Route::get('profile/course/course_certificate/{certificate_id}/edit', 'CertificateController@edit')->name('course.certificate.edit');
            Route::put('profile/course/course_certificate/{certificate_id}/edit', 'CertificateController@update')->name('course.certificate.update'); 
            Route::get('profile/course/{certificate_id}/certificate/create', 'CertificateController@create')->name('course.certificate.create');
            Route::post('profile/course/{certificate_id}/certificate/create', 'CertificateController@store')->name('course.certificate.store');
            Route::get('profile/course/{certificate_id}/certificate/preview','CertificateController@generatePDF')->name('course.certificate.preview');
            Route::get('profile/course/{certificate_id}/certificate/download','CertificateController@downloadPDF')->name('course.certificate.download');

            //Live Sessions
            Route::get('profile/course/{course_id}/live-sessions', 'LiveSessionController@list')->name('course.live-sessions.list');
            Route::get('profile/course-session/{live_session_id}/view-registration', 'LiveSessionController@viewRegistration')->name('course.live-session.view');
            Route::get('profile/course-session/{live_session_id}/edit', 'LiveSessionController@edit')->name('course.live-session.edit');
            Route::put('profile/course-session/{live_session_id}/edit', 'LiveSessionController@update')->name('course.live-session.update'); 
            Route::get('profile/course-session/{course_id}/create', 'LiveSessionController@create')->name('course.live-session.create');
            Route::post('profile/course-session/{course_id}/create', 'LiveSessionController@store')->name('course.live-session.store');
            Route::delete('profile/course-session/{live_session_id}', 'LiveSessionController@destroy')->name('course.live-session.destroy'); 
            // Summary
            Route::get('profile/summary/{id}', 'SummaryController@show')->name('summary.show');
            Route::get('profile/course-summary/{course_id}/create', 'SummaryController@create')->name('summary.create');
            Route::get('profile/course-summary/{summary_id}/edit', 'SummaryController@edit')->name('summary.edit');
            Route::delete('profile/course-summary/{summary_id}', 'SummaryController@destroy')->name('summary.destroy');          
            Route::post('profile/course-summary/{course_id}/create', 'SummaryController@store')->name('summary.store');
            Route::put('profile/course-summary/{summary_id}/edit', 'SummaryController@update')->name('summary.update');

            // Learning Activity
            Route::get('profile/learning-activity/{id}', 'LearningActivityController@show')->name('learning-activity.show');
            Route::get('profile/course-learning-activity/{course_id}/create', 'LearningActivityController@create')->name('learning-activity.create');
            Route::get('profile/course-learning-activity/{id}/edit', 'LearningActivityController@edit')->name('learning-activity.edit');
            Route::delete('profile/course-learning-activity/{id}', 'LearningActivityController@destroy')->name('learning-activity.destroy');          
            Route::post('profile/course-learning-activity/{course_id}/create', 'LearningActivityController@store')->name('learning-activity.store');
            Route::put('profile/course-learning-activity/{id}/edit', 'LearningActivityController@update')->name('learning-activity.update');

            // assignment
            Route::get('profile/assignment/{id}', 'AssignmentController@show')->name('assignment.show');
            Route::get('profile/assignment/{id}/user-assignment', 'AssignmentController@userAssignment')->name('assignment.detail');
            
            // Route::post('profile/user-assignment/edit-comment', 'AssignmentController@updateComment')->name('assignment.comment');
            Route::post('profile/user-assignment/edit-comment', 'AssignmentReviewController@reviewAssignment')->name('ajax-assignment-review');
            Route::get('profile/long_answer/{id}/user-long-answer/{courseId}', 'QuestionController@userLongAnswer')->name('long_answer.detail');
            Route::post('profile/user-long-answer/edit-comment', 'QuestionController@reviewAnswer')->name('ajax-long-answer-review');

            Route::middleware('canCRUDOnAssignment')
            ->group(function(){
                Route::get('profile/course-assignment/{course_id}/create', 'AssignmentController@create')->name('assignment.create')->middleware('canCRUDOnCourse');
                //Route::post('profile/course-assignment/{course_id}/create', 'AssignmentController@store')->name('assignment.store')->middleware('canCRUDOnCourse');
                Route::get('profile/course-assignment/{assignment_id}/edit', 'AssignmentController@edit')->name('assignment.edit');
                //Route::put('profile/course-assignment/{assignment_id}/edit', 'AssignmentController@update')->name('assignment.update');
                Route::delete('profile/course-assignment/{assignment_id}', 'AssignmentController@destroy')->name('assignment.destroy');
            });
            Route::post('profile/course-assignment/{course_id}/create', 'AssignmentController@store')->name('assignment.store');
            Route::put('profile/course-assignment/{assignment_id}/edit', 'AssignmentController@update')->name('assignment.update');

            // quiz
            Route::middleware('canCRUDOnQuiz')
            ->group(function(){
                //Route::post('profile/course-quiz/{course_id}/create', 'QuizController@store')->name('quiz.store')->middleware('canCRUDOnCourse');
                //Route::put('profile/course-quiz/{quiz_id}/edit', 'QuizController@update')->name('quiz.update');
                
            });
            Route::delete('profile/course-quiz/{quiz_id}', 'QuizController@destroy')->name('quiz.destroy');
            Route::get('profile/course-quiz/{course_id}/create', 'QuizController@create')->name('quiz.create')->middleware('canCRUDOnCourse');
            Route::post('profile/course-quiz/{course_id}/create', 'QuizController@store')->name('quiz.store');
            Route::put('profile/course-quiz/{quiz_id}/edit', 'QuizController@update')->name('quiz.update');
            Route::get('profile/course-quiz/{quiz_id}/edit', 'QuizController@edit')->name('quiz.edit');
            // question
            Route::middleware('canCRUDOnQuestion')
            ->group(function() {
                Route::get('profile/course-question/{quiz_id}/create', 'QuestionController@create')->name('question.create');
                //Route::post('profile/course-question/{quiz_id}/create', 'QuestionController@store')->name('question.store');
                
                //Route::put('profile/course-question/{question_id}/edit', 'QuestionController@update')->name('question.update');
                Route::delete('profile/course-question/{question_id}', 'QuestionController@destroy')->name('question.destroy');
            });      
            Route::get('profile/course-question/{question_id}/edit', 'QuestionController@edit')->name('question.edit'); 
            Route::post('profile/course-question/{quiz_id}/create', 'QuestionController@store')->name('question.store');
            Route::put('profile/course-question/{question_id}/edit', 'QuestionController@update')->name('question.update');           
            // Route::get('profile/user', 'UserController@index')
            //    ->name('user.index');

            //order setting
            Route::get('profile/course/{course_id}/order/edit', 'CourseController@editOrder')->name('course.order.edit');
            Route::put('profile/course/{course_id}/order/edit', 'CourseController@updateOrder')->name('course.order.update'); 

            Route::resource('profile/user', 'UserController')->only(['index', 'edit', 'update']);
            Route::get('profile/user/{id}/{action}', 'UserController@updateStatus')
                ->name('user.update-status');

            Route::get('profile/resource/create/{format}', 'ResourceController@create')
                ->name('resource.create-with-format');


            // Course Approval Requests
            Route::get('profile/course/{course_id}/submit', 'CourseApprovalRequestController@create')
                ->name('course.submit-request');//->middleware('canCRUDOnCourse');
            Route::post('profile/course/{course_id}/submit', 'CourseApprovalRequestController@store')
                ->name('course.save-submit-request');//->middleware('canCRUDOnCourse'); 

        });

        // Only Admin and Manager users can access Approval Requests and take actions
        Route::middleware('isAdminOrManager')
            ->group(function () {
                Route::resource('profile/article', 'ArticleController');
                
                // Course Approval
                Route::get('course-approval-request', 'CourseApprovalRequestController@index')->name('course-approval-request.index');
                Route::get('profile/course-request/{id}/{action}', 'CourseApprovalRequestController@updateStatus')
                    ->name('course-approval-request.update-status');
            });

        // Not only Admin and Manager users but also Teach Educator can view his/her Approval Request
        Route::get('approval-request/{id}', 'ApprovalRequestController@show')->name('approval-request.show');
        Route::get('course-approval-request/{id}', 'CourseApprovalRequestController@show')->name('course-approval-request.show');

        Route::get('syncgdrive', 'MediaController@syncGDrive')->name('media.syncgdrive');
        Route::get('profile/media/{id}/delete', 'MediaController@destroy')->name('media.destroy');
        Route::get('ajax-delete/{id}/media', 'MediaController@deleteMediaByResource')->name('ajax.media.destroy');

        //exporting
        Route::post('dashboard/exportSignups', 'DashboardController@export')->name('exportSignups');
    });
