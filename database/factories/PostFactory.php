<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);
        $status = fake()->randomElement(PostStatus::cases());

        $publishedAt = null;
        if ($status === PostStatus::Published) {
            $publishedAt = fake()->dateTimeBetween('-60 days', '-1 day');
        }
        if ($status === PostStatus::Scheduled) {
            $publishedAt = fake()->dateTimeBetween('+1 day', '+30 days');
        }

        return [
            'category_id' => Category::factory(),
            'author_id' => User::factory(),

            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 9999),

            'excerpt' => fake()->optional()->paragraph(),
            'content' => fake()->optional()->paragraphs(6, true),

            'status' => $status->value,
            'published_at' => $publishedAt,

            'meta_title' => fake()->optional()->sentence(6),
            'meta_description' => fake()->optional()->text(140),

            'og_title' => fake()->optional()->sentence(6),
            'og_description' => fake()->optional()->text(160),
            'og_image' => null,
        ];
    }
}
