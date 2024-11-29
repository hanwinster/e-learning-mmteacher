<?php

use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = collect([
            'General',
            'Learning',
            'eLibrary',
        ]);

        $data = $categories->map(function ($item) {
            return ['title' => $item, 'slug' => str_slug($item)];
        });

        $data->each(function ($post) {
            /*             $category = factory(App\Models\FaqCategory::class)->create($post)->each(function ($category) {
                            $category->faqs()->save(factory(App\Models\Faq::class)->make());
                            $this->command->info('Seeding FAQs for Category ['.$category->title.']');
                        }); */

            $category = factory(App\Models\FaqCategory::class)->create($post);

            $count = 10;
            // // $subject->resources()->save(factory(Resource::class, 10)->make());
            factory(App\Models\Faq::class, $count)->create(['category_id' => $category->id]);
            $this->command->info('Seeding '.$count.' FAQs for Category ['.$category->title.']');
        });
    }
}
