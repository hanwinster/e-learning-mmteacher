<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Resource::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'description' => $faker->paragraph,
        'resource_format' => 'image',
        'published' => rand(0, 1),
        'approved' => rand(0, 1),

        'author' => $faker->name,
        'publisher' => $faker->name,
        'publishing_year' => $faker->year($max = 'now')  ,
        'strand' => $faker->sentence,
        'sub_strand' => $faker->sentence,
        'rating' => rand(0, 5),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'license_id' => function () {
            return factory(App\Models\License::class)->create()->id;
        },
        'suitable_for_ec_year' => rand(1, 5),
        'url' => $faker->url,
        'total_page_views' => rand(0, 1000),
        'total_downloads' => rand(0, 1000),
        'allow_download' => rand(0, 1),
        'allow_feedback' => rand(0, 1),
    ];
});

$factory->state(App\Models\Resource::class, 'published', [
    'published' => true
]);

$factory->state(App\Models\Resource::class, 'unpublished', [
    'published' => false
]);
