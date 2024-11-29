<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath',
    ],
], function () {

    Auth::routes();
    Route::get('testses', [
        'as' => 'testses',
        'uses' => 'Auth\RegisterController@test',
    ]);

    Route::post('/loginViaDialog', [
        'as' => 'auth.login-via-dialog',
        'uses' => 'Auth\LoginController@loginViaDialog',
    ]);

    // Route::post('customLogout', [
    //     'as' => 'auth.custom-logout', 
    //     'uses' => 'Auth\LoginController@customLogout',
    // ]);
    Route::get('verify/logout', 'Auth\VerifyOTPController@customLogout')->name('auth.custom-logout');  

    // Account Verification
    Route::get('verify/get_otp', [
        'as' => 'auth.verify.get_otp',
        'uses' => 'Auth\VerifyOTPController@getOTP',
    ]);

    Route::post('verify/post_otp', [
        'as' => 'auth.verify.post_otp',
        'uses' => 'Auth\VerifyOTPController@verifyOTP',
    ]);

    Route::get('request/otp', [
        'as' => 'auth.request.otp',
        'uses' => 'Auth\VerifyOTPController@requestOTP',
    ]);

    Route::post('resend/otp', [
        'as' => 'auth.resend.otp',
        'uses' => 'Auth\VerifyOTPController@resendOTP',
    ]);

    // Forgot Password
    Route::get('choose/password_reset_option', [
        'as' => 'auth.get.password_reset_option',
        'uses' => 'Auth\SendPasswordResetTokenController@chooseOption',
    ]);

    Route::post('choose/password_reset_option', [
        'as' => 'auth.post.password_reset_option',
        'uses' => 'Auth\SendPasswordResetTokenController@redirectOption',
    ]);

    Route::get('request/credientials', [
        'as' => 'auth.get.request_credientials',
        'uses' => 'Auth\SendPasswordResetTokenController@requestCredentials',
    ]);

    Route::post('reset-password/send_reset_token', [
        'as' => 'auth.reset-password.send_reset_token',
        'uses' => 'Auth\SendPasswordResetTokenController@sendPasswordResetToken',
    ]);

    Route::get('reset-password/get-token', [
        'as' => 'auth.reset-password.get-token',
        'uses' => 'Auth\SendPasswordResetTokenController@getToken',
    ]);

    Route::post('reset-password/verify-token', [
        'as' => 'auth.reset-password.verify-token',
        'uses' => 'Auth\SendPasswordResetTokenController@verifyToken',
    ]);

    Route::get('reset-password/{token}', [
        'as' => 'auth.get.reset-password',
        'uses' => 'Auth\SendPasswordResetTokenController@showPasswordResetForm',
    ]);

    Route::post('reset-password/{token}', [
        'as' => 'auth.post.reset-password',
        'uses' => 'Auth\SendPasswordResetTokenController@resetPassword',
    ]);

    require base_path() . '/routes/backend.php';
    require base_path() . '/routes/member.php';

    //Route::get('/', 'HomeController@index')->name('home');
    Route::get('/e-learning', 'HomeController@index')->name('home');
    Route::get('/user-manuals/{id}', 'HomeController@showManuals')->name('user-manuals'); 
    Route::get('/terms-and-conditions', 'HomeController@termsAndPrivacy')->name('terms-privacy'); 
    Route::get('/', 'HomeController@showHome')->name('mm-teacher-platform'); //sample-landing-pg
    Route::get('/other-resources', 'HomeController@showPartners')->name('other-resources'); 

    Route::get('/search', 'SearchController@index')->name('search.index');
    //Route::get('/search/advanced', 'AdvancedSearchController@index')->name('search.advanced');

    Route::get('/subject/{slug}', 'SubjectController@show')->name('subject.show');

    Route::get('/media', 'ArticleController@index')->name('article.index');
    Route::get('/article/{slug}', 'ArticleController@show')->name('article.show');
    Route::get('/article/category/{slug}', 'ArticleCategoryController@show')->name('article.category');

    
    Route::post('/quiz/check-answer', 'QuizController@checkAnswer')->name('quiz.check-answer');
    Route::post('/quiz/check-blank-answer', 'QuizController@checkBlankAnswer')->name('quiz.check-blank-answer');
    
    Route::post('/update-quiz-prev-completion', 'QuizController@updateCompletionPrev')
            ->name('courses.update-quiz-prev-completion');
    Route::post('/update-quiz-next-completion', 'QuizController@updateCompletionNext')
            ->name('courses.update-quiz-next-completion');

    Route::get('/take-course-guest/{course}', 'CourseController@takeCourseGuest')
            ->name('courses.take-course-guest');

    Route::group(['middleware' => 'auth', 'prefix' => 'e-learning/courses'], function () {
        Route::get('/my-course-view', 'CourseController@myCourses')
            ->name('courses.my-courses');
        Route::get('/take-course/{course}', 'CourseController@takeCourse')
            ->name('courses.take-course');
        
        Route::get('/cancel-course/{course}', 'CourseController@cancelCourse')
            ->name('courses.cancel-course');
        Route::get('/learning/{course}', 'CourseController@viewCourse')
            ->name('courses.view-course');
        Route::get('/lecture/{lecture}', 'CourseController@learnCourse')
            ->name('courses.learn-course'); 
        Route::get('/quiz/{id}', 'QuizController@showQuiz')->name('quiz.show'); 
        Route::post('/quiz-assignment/{assignment}', 'QuizController@submitAssignment')
            ->name('courses.submit-quiz-assignment');
        Route::post('/quiz-long-answer', 'QuizController@submitLongAnswer')
            ->name('courses.submit-long-answer-quiz');
        Route::get('/assignment/{assignment}', 'AssignmentController@show')
            ->name('courses.view-assignment');
        Route::get('/live-session/{session}', 'LiveSessionController@show')
            ->name('courses.view-live-session');
        Route::post('/update-session-completion', 'LiveSessionController@updateCompletion')
            ->name('courses.update-session-completion'); 
        Route::post('/update-session-prev-completion', 'LiveSessionController@updateCompletionPrev')
            ->name('courses.update-session-prev-completion'); 
        Route::post('/update-session-next-completion', 'LiveSessionController@updateCompletionNext')
            ->name('courses.update-session-next-completion'); 
        Route::get('/summary/{summary}', 'SummaryController@show')
            ->name('courses.summary'); 
        Route::post('/update-summary-completion', 'SummaryController@updateCompletion')
            ->name('courses.update-summary-completion'); 
        Route::post('/update-summary-prev-completion', 'SummaryController@updateCompletionPrev')
            ->name('courses.update-summary-prev-completion'); 
        Route::post('/update-summary-next-completion', 'SummaryController@updateCompletionNext')
            ->name('courses.update-summary-next-completion'); 

        Route::get('/learning-activity/{learning_activity}', 'LearningActivityController@show')
            ->name('courses.learning-activity'); 
        Route::post('/update-learning-activity-completion', 'LearningActivityController@updateCompletion')
            ->name('courses.update-learning-activity-completion'); 
        Route::post('/update-learning-activity-prev-completion', 'LearningActivityController@updateCompletionPrev')
            ->name('courses.update-learning-activity-prev-completion'); 
        Route::post('/update-learning-activity-next-completion', 'LearningActivityController@updateCompletionNext')
            ->name('courses.update-learning-activity-next-completion'); 

        Route::get('/assessment/{assessment}', 'AssessmentController@show')
            ->name('courses.assessment'); 
        Route::post('/assessment/submit-assessment', 'AssessmentController@store')
            ->name('courses.create-assessment'); 
        Route::put('/assessment//submit-assessment/{id}', 'AssessmentController@update')
            ->name('courses.update-assessment');
        Route::get('/evaluation/{course}/evaluation', 'EvaluationController@show')
            ->name('courses.evaluation'); 
        Route::post('/evaluation/submit-evaluation', 'EvaluationController@store')
            ->name('courses.create-evaluation'); 
        Route::put('/evaluation//submit-evaluation/{id}', 'EvaluationController@update')
            ->name('courses.update-evaluation');
        
        Route::post('/live-session/register', 'LiveSessionController@registerSession')
            ->name('courses.register-session'); 
        Route::post('/assignment/{assignment}', 'AssignmentController@submitAssignment')
            ->name('courses.submit-assignment');
        Route::get('/assignment/view-feedback/{assignment_user}', 'AssignmentController@viewFeedback')
            ->name('courses.view-assignment-feedback');
        Route::get('/download-lecture/{lecture}', 'CourseController@downloadLecture')
            ->name('courses.download-lecture');
        Route::get('/download-course/{course}', 'CourseController@downloadCourse')
            ->name('courses.download-course');
        Route::post('/update-clect-prev-completion', 'CourseController@updateCompletionPrev')
            ->name('courses.update-clect-prev-completion');
        Route::post('/update-clect-next-completion', 'CourseController@updateCompletionNext')
            ->name('courses.update-clect-next-completion');
        Route::post('/learner-generate-certificate', 'CourseController@generateCertificate')
            ->name('courses.learner-generate-certificate');
    });
    Route::get('/courses', 'CourseController@index')->name('courses.index');
    Route::get('/e-learning/index', 'CourseController@index')->name('elearning.index');
    Route::get('/e-learning/browse', 'CourseController@browse')->name('elearning.browse');
    Route::get('/e-learning/browse-category', 'CourseController@browseByCategory')->name('elearning.browseByCategory');
    Route::get('/e-learning/courses/{course}', 'CourseController@show')->name('courses.show');
    Route::get('/e-learning/courses', 'CourseController@filterCourses')->name('filter-course');


    Route::get('/faq', 'FaqController@index')->name('faq.index');
    Route::get('/faq/{slug}', 'FaqController@show')->name('faq-category.show');

    Route::get('/@{username}', 'ProfileController@show')->name('profile.show');
    Route::get('/contact-us', 'ContactController@show')->name('contact-us.show');
    Route::post('/contact-us', 'ContactController@sendContact')->name('contact-us.post');

    Route::get('/{slug}', 'PageController@show')->name('page.show');
});

Route::get('generate-pdf','PDFController@generatePDF');
//Route::get('/', function () {
//    //return view('welcome');
//    return redirect()->to(config('app.locale'), 301);
//})->name('home');
Route::get('/offline', function () {    
    return view('vendor/laravelpwa/offline');
});