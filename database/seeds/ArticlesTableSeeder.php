<?php

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ArticleCategory;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'title' => 'News',
                'slug' => str_slug('News'),
                'published' => 1,
            ],
            [
                'title' => 'Announcements',
                'slug' => str_slug('Announcements'),
                'published' => 1,
            ],
            [
                'title' => 'Articles',
                'slug' => str_slug('Articles'),
                'published' => 1,
            ],
        ];

        foreach ($categories as $category) {
            $c = ArticleCategory::create($category);
            $c->articles()->save(factory(Article::class)->make());
        }
    }
}
