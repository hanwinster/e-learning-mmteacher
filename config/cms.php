<?php

return [
    'backend_uri' => env('APP_BACKEND_URI', 'admin'),

    'google_analytics_key' => env('GA_KEY', ''),
    'enable_google_analytics' => env('ENABLE_GA', false),
    'recaptcha_key' => env('GOOGLE_RECAPTCHA_KEY', 'test'),
    'recaptcha_secret' => env('GOOGLE_RECAPTCHA_SECRET', ''),
    'enable_preloader' => env('ENABLE_PRELOADER', false),

    'sharing_enabled' => env('SHARING_ENABLED', false),
    'sharing_publisher_id' => env('SHARING_PUBLISHER_ID', ''),
    'order_email_2' => env('ORDER_EMAIL_2', ''),
    'vimeo_client' => env('VIMEO_CLIENT', ''),
    'vimeo_secret' => env('VIMEO_SECRET', ''),
    'vimeo_access_token' => env('VIMEO_ACCESS', ''),
    'search_operator' => env('SEARCH_OPERATOR', 'LIKE'),
    'enable_article_notification' => env('ENABLE_ARTICLE_NOTIFICATION', false),

    'breadcrumb_title_ch_limit' => env('BREADCRUMB_TITLE_LIMIT', 150),
    'lecture_title_right_navi_ch_limit' => env('LECTURE_TITLE_RIGHT_NAVI_LIMIT', 40),
    'quiz_title_right_navi_ch_limit' => env('QUIZ_TITLE_RIGHT_NAVI_LIMIT', 25),
    'course_default_grace_period_to_notify' => 10,
    'course_default_estimated_duration' => 4,
    'course_default_estimated_duration_unit' => 'week(s)',
    'certificate_default_title' => 'Certificate of Completion',
    'certificate_certify_text' => 'This is to certify that',
    'certificate_completion_text' => 'has successfully completed ',
    'categories-and-keywords' => [ 
        1 => 'ict',
        4 => 'curriculum',
        5 => 'mil',
        10=> 'esd',
        12=> 'hiv'

    ],
    'alphabets' =>  [ 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
    'rearrange_numbers' => [ 'one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen','twenty','twentyone','twentytwo','twentythree','twentyfour','twentyfive','twentysix','twentyseven','twentyeight', 'twentynine','thirty']
];
