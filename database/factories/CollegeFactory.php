<?php

use Faker\Generator as Faker;

$factory->define(App\Models\College::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'title' => $name,
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'published' => 1
    ];
});
