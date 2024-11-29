<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ResourcePrivacy::class, function (Faker $faker) {
    return [
        'resource_id' => function () {
            return factory(App\Models\Resource::class)->create()->id;
        },
        'user_type' => key(rand(UserRepository::getTypes())),
        'role_id' => function () {
            return factory(App\Role::class)->create()->id;
        }
    ];
});
