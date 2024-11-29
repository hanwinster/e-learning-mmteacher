<?php

return [
    'meta_title' => env('META_TITLE'),
    'meta_description' => env('META_DESCRIPTION'),
    'meta_keywords' => env('META_KEYWORDS'),

    'og_type' => env('OG_TYPE', "website"),
    'og_url' => env('OG_URL'),
    'og_title' => env('OG_TITLE'),
    'og_description' => env('OG_DESCRIPTION'),
    'og_image' => env('OG_IMAGE'),
    //'og_image_type' => env('OG_IMAGE_TYPE', "image/png"), 
    'og_site_name' => env('OG_SITE_NAME'),
    'og_locale' => env('OG_LOCALE'),
    'og_image_width' => env('OG_IMAGE_WIDTH', 200),
    'og_image_height' => env('OG_IMAGE_HEIGHT', 400),

    'twitter_card' => env('TWITTER_CARD'),
    'twitter_site' => env('TWITTER_SITE'),
    'twitter_creator' => env('TWITTER_CREATOR'),
    'twitter_url' => env('TWITTER_URL'),
    'twitter_title' => env('TWITTER_TITLE'),
    'twitter_description' => env('TWITTER_DESCRIPTION'),
    'twitter_image' => env('TWITTER_IMAGE'),
];
