<?php

use Faker\Generator as Faker;

$factory->define(App\Models\License::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence,
        'slug' => str_slug($title),
        'description' => $faker->paragraph,
        'published' => rand(0, 1)
    ];
});
