<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Subject::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'description' => $faker->paragraph,
        'weight' => 0,
        'published' => rand(0, 1),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
