<?php

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Resource;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = collect([
            'Art',
            'Educational Studies',
            'English',
            'ICT',
            'Life Skills',
            'Local Curriculum',
            'Mathematics',
            'Morality and Civics',
            'Myanmar',
            'Physical Education',
            'Practicum',
            'Reflective Practice and Essential Skills',
            'Science',
            'Social Studies',
        ]);

        $data = $subjects->map(function ($item) {
            return ['title' => $item, 'slug' => str_slug($item)];
        });

        $data->each(function ($post) {
            $subject = factory(Subject::class)->create($post);
            // $subject->resources()->save(factory(Resource::class, 10)->make());
            $count = 10;
            factory(Resource::class, $count)->create();
            $this->command->info('Seeding '.$count.' resources for subject ['.$subject->title.']');
        });
    }
}
