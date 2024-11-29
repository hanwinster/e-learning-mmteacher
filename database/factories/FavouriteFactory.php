<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Favourite::class, function (Faker $faker) {
    return [
        'resource_id' => function () {
            return factory(App\Models\Resource::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
