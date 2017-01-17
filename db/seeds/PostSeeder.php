<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
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
                'title' => 'Test Post',
                'slug' => 'test-post',
                'content' => $faker->paragraphs($nb = 3, $asText = true),
                'published_at' => $faker->date($format = 'Y-m-d', $max = 'now')
            ],
            [
                'title' => 'Test Post 3',
                'slug' => 'test-post-3',
                'content' => $faker->paragraphs($nb = 3, $asText = true),
                'published_at' => $faker->date($format = 'Y-m-d', $max = 'now')
            ],
            [
                'title' => $faker->sentence(),
                'slug' => $faker->slug($nbWords = 6),
                'content' => $faker->paragraphs($nb = 3, $asText = true),
                'published_at' => $faker->date($format = 'Y-m-d', $max = 'now')
            ],
        ];

        $this->insert('posts', $data);
    }
}
