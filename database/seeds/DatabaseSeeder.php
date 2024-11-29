<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(ResourcesTableSeeder::class);
        $this->call(LaravelPermissionTablesSeeder::class);
    }
}
