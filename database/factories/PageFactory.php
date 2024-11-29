<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Page::class, function (Faker $faker) {
    return [
        'title' => $name = $faker->sentence,
        'second_title' => $faker->sentence,
        'slug' => str_slug($name),
        'body' => $faker->paragraph,
        'published' => rand(0, 1),
        'show_title' => 1
    ];
});
