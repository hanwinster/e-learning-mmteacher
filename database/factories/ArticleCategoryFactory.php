<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ArticleCategory::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'title' => $name,
        'slug' => str_slug($name),
        'body' => $faker->paragraph,
        'published' => 1
    ];
});
