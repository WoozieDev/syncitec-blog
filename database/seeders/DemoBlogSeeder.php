<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Author: usa un user existente si hay, si no crea uno
        $author = User::query()->first() ?? User::factory()->create([
            'name' => 'Demo Author',
            'email' => 'author@example.com',
        ]);

        $categories = Category::factory()->count(8)->create();
        $tags = Tag::factory()->count(20)->create();

        // Crear posts y asignar category + author fijo
        $posts = Post::factory()
            ->count(35)
            ->state(fn () => [
                'author_id' => $author->id,
                'category_id' => $categories->random()->id,
            ])
            ->create();

        // Attach tags a cada post (0..5 tags)
        foreach ($posts as $post) {
            $post->tags()->sync(
                $tags->random(rand(0, 5))->pluck('id')->all()
            );
        }
    }
}
