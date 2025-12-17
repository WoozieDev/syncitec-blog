<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->publicWithRelations()
            ->publicLatest()
            ->paginate(10)
            ->withQueryString();

        $featured = Post::query()
            ->publicWithRelations()
            ->publicLatest()
            ->first();

        $categories = \App\Models\Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $trendingTags = Tag::query()
            ->withCount([
                'posts as posts_count' => fn ($q) => $q->visible(),
            ])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Home', [
            'title' => 'Blog',
            'featured' => $featured ? [
                'title' => $featured->title,
                'slug' => $featured->slug,
                'excerpt' => $featured->excerpt,
                'published_at' => optional($featured->published_at)->toDateTimeString(),
                'category' => $featured->category,
                'author' => $featured->author,
                'tags' => $featured->tags,
            ] : null,
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $trendingTags,
        ]);
    }

    public function show(string $slug): Response
    {
        $post = Post::query()
            ->publicWithRelations()
            ->visibleBySlug($slug)
            ->firstOrFail();

        return Inertia::render('Blog', [
            'title' => $post->title,
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'published_at' => optional($post->published_at)->toDateTimeString(),
                'category' => $post->category,
                'author' => $post->author,
                'tags' => $post->tags,
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'og_title' => $post->og_title,
                'og_description' => $post->og_description,
                'og_image' => $post->og_image,
            ],
        ]);
    }

    public function category(string $slug): Response
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::query()
            ->publicWithRelations()
            ->publicLatest()
            ->where('category_id', $category->id)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Category', [
            'title' => $category->name,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'posts' => $posts,
        ]);
    }

    public function tag(string $slug): Response
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::query()
            ->publicWithRelations()
            ->publicLatest()
            ->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Tag', [
            'title' => $tag->name,
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ],
            'posts' => $posts,
        ]);
    }
}
