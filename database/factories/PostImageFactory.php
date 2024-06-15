<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostImageFactory extends Factory
{
    protected $model = PostImage::class;

    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'image_name' => $this->faker->imageUrl(800, 600), // Dummy image URL from faker
        ];
    }

    /**
     * Indicate that the image is a placeholder.
     *
     * @return \Database\Factories\PostImageFactory
     */
    public function placeholder()
    {
        return $this->state(function (array $attributes) {
            return [
                'image_name' => 'https://picsum.photos/200/300', // Placeholder image URL
            ];
        });
    }
}
