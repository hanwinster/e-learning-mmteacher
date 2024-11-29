<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'username' => str_slug($name),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'type' => null,
        'approved' => 0,
        'blocked' => 0,
    ];
});

$factory->state(App\User::class, 'admin', [
    'type' => App\User::TYPE_ADMIN
]);

$factory->state(App\User::class, 'manager', [
    'type' => App\User::TYPE_MANAGER
]);

$factory->state(App\User::class, 'teacher_educator', [
    'type' => App\User::TYPE_TEACHER_EDUCATOR
]);

$factory->state(App\User::class, 'student_teacher', [
    'type' => App\User::TYPE_STUDENT_TEACHER
]);
