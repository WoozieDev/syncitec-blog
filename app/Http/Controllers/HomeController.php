<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

        return Inertia::render('Home', [
            'title' => 'Blog',
            'posts' => $posts,
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
}
