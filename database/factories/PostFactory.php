<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence;
        $paragraphs = $this->faker->paragraphs(rand(3, 7));
        $htmlContent = '';

        foreach ($paragraphs as $index => $paragraph) {
            $htmlContent .= '<p>' . $paragraph . '</p>';
            if ($index === 1) { // Add image after the second paragraph
                $imageUrl = 'https://picsum.photos/seed/' . $this->faker->unique()->md5 . '/800/600';
                $htmlContent .= '<figure class="image image_resized" style="width:100%;">';
                $htmlContent .= '<img class="post-content-image" style="aspect-ratio:800/600;" src="' . $imageUrl . '" alt="' . $this->faker->sentence() . '">';
                $htmlContent .= '</figure>';
            }
        }

        // Add random additional elements
        if ($this->faker->boolean(30)) {
            $htmlContent .= '<blockquote>' . $this->faker->sentence . '</blockquote>';
        }

        if ($this->faker->boolean(20)) {
            $htmlContent .= '<ul><li>' . implode('</li><li>', $this->faker->words(rand(3, 5))) . '</li></ul>';
        }

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $htmlContent,
        ];
    }
}
