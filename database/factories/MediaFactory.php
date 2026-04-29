<?php

namespace Database\Factories;

use App\Enums\MediaType;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'media/'.$this->faker->word().'.jpg',
            'type' => MediaType::IMAGE,
            'collection_name' => 'default',
            'mediable_id' => 1,
            'mediable_type' => 'App\Models\Post',
        ];
    }
}
