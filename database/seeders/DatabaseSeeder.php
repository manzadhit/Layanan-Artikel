<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Menghapus data user yang ada
        User::truncate();

        // Create users
        $users = User::factory()->count(30)->create()->each(function ($user) {
            // Generate username from first part of the name and ensure uniqueness
            $username = Str::slug(Str::words($user->name, 1, '')) . '-' . uniqid();
            $user->username = $username;
            $user->save();
        });

        // Create categories
        $categories = Category::factory()->count(5)->create();

        // Create posts
        Post::factory(20)->create()->each(function ($post) use ($categories) {
            // Attach random categories to posts
            $categoriesToAttach = $categories->random(rand(1, 3))->pluck('id');
            $post->categories()->attach($categoriesToAttach);
        });
    }

}
