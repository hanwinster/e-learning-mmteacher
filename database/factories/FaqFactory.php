<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Faq::class, function (Faker $faker) {
    return [
        'category_id' => function () {
            return factory(App\Models\FaqCategory::class)->create()->id;
        },
        'question' => $faker->paragraph,
        'answer' => $faker->paragraph,
        'published' => rand(0, 1),
    ];
});
