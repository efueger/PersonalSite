<?php

use Phinx\Seed\AbstractSeed;

class ProjectSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [
            [
                'title' => 'URLs',
                'slug' => 'urls',
                'description' => $faker->paragraphs(3, true),
                'live_url' => $faker->url,
                'github_url' => $faker->url,
                'technologies' => $faker->sentence(),
                'published_at' => $faker->date('Y-m-d', 'now'),
                'preview' => $faker->word
            ],
            [
                'title' => 'We Are Social',
                'slug' => 'we-are-social',
                'description' => 'Final Project',
                'live_url' => $faker->url,
                'github_url' => $faker->url,
                'technologies' => $faker->sentence(),
                'published_at' => $faker->date('Y-m-d', 'now'),
                'preview' => $faker->word
            ]
        ];

        $this->insert('projects', $data);
    }
}
