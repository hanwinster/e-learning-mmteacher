<?php

use Illuminate\Http\Request;

Route::namespace('API\Guest')->name('api.guest.')->group(function () {
    /* Home Page APIs */
    Route::apiResource('slide', 'SlideController')->only(['index','show']);
    Route::get('get-home-page-courses', 'CourseController@getCoursesForHomePage'); 
    Route::get('search-courses', 'CourseController@showSearchResults');
    Route::get('course-categories', 'ListingController@getCourseCategories');
    Route::get('course-categories/{id}', 'ListingController@getCourseCategoryById');
    Route::get('region-state', 'ListingController@getRegionsAndStates'); 
    Route::apiResource('pages', 'PageController')->only(['index','show']); // about-us
    Route::get('home-page-video', 'ListingController@getHomeVideoLink');
    Route::get('user-manuals', 'ListingController@getUserManualLinks');
    Route::get('get-terms', 'ListingController@getTermsAndConditions');
    Route::get('get-privacy', 'ListingController@getPrivacyPolicy');
    Route::apiResource('faq', 'FaqController')->only(['index','show']);
    Route::apiResource('faq-category', 'FaqCategoryController')->only(['index','show']);
    Route::get('faq-category/{slug}/faqs', 'FaqCategoryController@getFaqs');
    Route::post('contact-us', 'ContactController@saveContact');
    /* end of Home Page APIs */

    /* Drop-down / General APIs */
    Route::get('accessible-right', 'ListingController@getAccessibleRights');
    Route::get('user-type', 'ListingController@getUserTypes');
    Route::get('notification-channels', 'ListingController@getNotificationChannel');
    Route::get('colleges', 'ListingController@getCollege');
    Route::get('year-study-teaching', 'ListingController@getYearofTeaching');
    Route::get('gender-values', 'ListingController@getGender');
    /* end of Drop-down / General APIs */

    
    
    
    Route::post('change-language', 'ListingController@changeLanguage');
    //Route::get('course/{courseId}/rating-reviews', 'RatingReviewController@index');
    Route::get('courses/all', 'CourseController@index');
    Route::get('courses/{id}', 'CourseController@show');
    Route::get('courses/{id}/get-related-resources', 'CourseController@getRelatedResources');  
    Route::get('courses/{id}/last-updated-time', 'CourseController@getLastUpdatedOfACourse');
    //Route::get('courses/{id}', 'CourseController@show');
    Route::get('course-categories', 'CourseController@getCourseCategories');
    Route::get('course-levels', 'CourseController@getCourseLevels');

    //Route::get('article-category', 'ListingController@getArticleCategories');
    //Route::get('/article', 'ArticleController@index')->name('api.article.index');
    //Route::get('/article/{id}', 'ArticleController@show')->name('api.article.show');
    //Route::get('/article/category/{slug}', 'ArticleCategoryController@show')->name('api.article.category');

    
    //Route::get('resource/{id}/related', 'RelatedResourceController@show')->name('api.resource.related');

    

    
});

    // Route::namespace('API\Guest')
    //     ->name('api.user.')
    //     ->prefix('user')
    //     // ->middleware('auth:api')
    //     ->group(function () {
    //         Route::apiResource('advanced-search', 'AdvancedSearchController')->only(['index','show']);
    //         Route::apiResource('search', 'SearchController')->only(['index','show']);
    //         Route::apiResource('resource', 'ResourceController')->only(['index','show']);
    // });
