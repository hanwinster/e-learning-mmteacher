<?php

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Resource::class, 50)
        ->states('published')
        ->create();

        factory(Resource::class, 10)
        ->states('unpublished')
        ->create();
    }
}
