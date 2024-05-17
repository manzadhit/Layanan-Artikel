<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->count(10)->create();
        $categories = Category::factory()->count(5)->create();
        $posts = Post::factory()->count(20)->create();

        // Attach categories to posts
        foreach ($posts as $post) {
            $categoriesToAttach = $categories->random(rand(1, 3))->pluck('id');
            $post->categories()->attach($categoriesToAttach);
        }
    }
}
