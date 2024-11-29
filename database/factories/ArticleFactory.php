<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Article::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'second_title' => $faker->paragraph,
        'slug' => str_slug($title),
        'published' => 1,
        'body' => $faker->paragraphs(rand(3, 10), true),
        'category_id' => function () {
            return factory(App\Models\ArticleCategory::class)->create()->id;
        },
    ];
});
