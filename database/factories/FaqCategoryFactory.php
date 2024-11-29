<?php

use Faker\Generator as Faker;

$factory->define(App\Models\FaqCategory::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence,
        'slug' => str_slug($title),
        'body' => $faker->paragraph,
        'published' => rand(0, 1),
    ];
});
