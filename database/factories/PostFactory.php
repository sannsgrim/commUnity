<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'caption' => $this->faker->sentence(),
            'up_votes' => 0,
            'down_votes' => 0,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Post $post) {
            PostImage::factory()->count(rand(1, 5))->create(['post_id' => $post->id]);
        });
    }
}
