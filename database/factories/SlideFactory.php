<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Slide::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'weight' => rand(0, 10),
        'published' => rand(0, 1)
    ];
});
