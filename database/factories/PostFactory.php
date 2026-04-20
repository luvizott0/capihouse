<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
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
            'content' => fake()->paragraph(),
            'media' => null,
            'likes_count' => fake()->numberBetween(0, 50),
            'comments_count' => fake()->numberBetween(0, 20),
        ];
    }

    /**
     * Indicate that the post has images.
     */
    public function withImages(int $count = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'media' => collect(range(1, $count))->map(fn () => [
                'type' => 'image',
                'url' => 'https://picsum.photos/seed/'.fake()->word().'/600/400',
            ])->all(),
        ]);
    }

    /**
     * Indicate that the post has a video.
     */
    public function withVideo(): static
    {
        return $this->state(fn (array $attributes) => [
            'media' => [
                ['type' => 'video', 'url' => 'https://example.com/video.mp4'],
            ],
        ]);
    }
}
