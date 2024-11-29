<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create an admin account
        factory(User::class)
        ->states(User::TYPE_ADMIN)
        ->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'approved' => 1
        ]);

        // create manager accounts
        $manager = factory(User::class)
        ->states(User::TYPE_MANAGER)
        ->create([
            'name' => 'Mr. Manager',
            'email' => 'test.manager@example.com',
            'password' => bcrypt('123456'),
            'approved' => 1
        ]);

        $managers = factory(User::class, 10)
        ->states(User::TYPE_MANAGER)
        ->create([
            // 'email' => 'admin@example.com',
            'password' => bcrypt('manager*')
        ]);

        // create teacher educator accounts
        $exporter = factory(\App\User::class)
        ->states(User::TYPE_TEACHER_EDUCATOR)
        ->create([
            'name' => 'Mr. Educator',
            'email' => 'test.educator@example.com',
            'password' => bcrypt('123456'),
            'approved' => 1
        ]);

        $exporters = factory(\App\User::class, 10)
        ->states(User::TYPE_TEACHER_EDUCATOR)
        ->create(
            [
            // 'email' => 'admin@example.com',
            'password' => bcrypt('educators*')
        ]
        );

        // create teacher educator accounts
        $exporter = factory(\App\User::class)
        ->states(User::TYPE_STUDENT_TEACHER)
        ->create([
            'name' => 'Mr. Student',
            'email' => 'test.student@example.com',
            'password' => bcrypt('123456'),
            'approved' => 1
        ]);

        $exporters = factory(\App\User::class, 10)
        ->states(User::TYPE_STUDENT_TEACHER)
        ->create(
            [
            // 'email' => 'admin@example.com',
            'password' => bcrypt('student*')
        ]
        );
    }
}
