<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = collect([
            [
                'title' => $title = 'About Us',
                'slug' => str_slug($title),
                'published' => true
            ],
            [
                'title' => $title = 'Disclaimer',
                'slug' => str_slug($title),
                'published' => true
            ],
            [
                'title' => $title = 'Contact Us',
                'slug' => str_slug($title),
                'published' => true
            ]
        ]);

        $pages->each(function ($page) {
            factory(App\Models\Page::class)->create(
                ['title' => $page['title'],
                'published' => $page['published'],
                'slug' => $page['slug']
                ]
            );
        });
    }
}
