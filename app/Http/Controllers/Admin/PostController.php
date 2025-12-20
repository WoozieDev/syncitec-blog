<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Post::class);

        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'status' => $request->string('status')->toString() ?: null, // draft|published|scheduled
            'category_id' => $request->integer('category_id') ?: null,
            'trashed' => $request->string('trashed')->toString() ?: null, // null|with|only
        ];

        $posts = Post::query()
            ->with([
                'category:id,name',
                'author:id,name',
            ])
            ->when($filters['trashed'] === 'with', fn ($q) => $q->withTrashed())
            ->when($filters['trashed'] === 'only', fn ($q) => $q->onlyTrashed())
            ->when(filled($filters['search']), function ($q) use ($filters) {
                $q->where(function ($qq) use ($filters) {
                    $qq->where('title', 'like', '%'.$filters['search'].'%')
                       ->orWhere('slug', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when(filled($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(filled($filters['category_id']), fn ($q) => $q->where('category_id', $filters['category_id']))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/posts/Index', [
            'title' => 'Posts',
            'posts' => $posts,
            'filters' => $filters,
            'categories' => $categories,
            'statusOptions' => array_map(fn ($s) => $s->value, PostStatus::cases()),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Post::class);

        return Inertia::render('admin/posts/Create', [
            'title' => 'Create post',
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
            'statusOptions' => array_map(fn ($s) => $s->value, PostStatus::cases()),
        ]);
    }

    public function store(StorePostRequest  $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $data = $request->normalized();

        // Enforce author
        $data['author_id'] = $request->user()->id;

        $post = Post::create($data);

        $post->tags()->sync($data['tag_ids'] ?? []);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post);

        $post->load('tags:id');

        return Inertia::render('admin/posts/Edit', [
            'title' => 'Edit post',
            'post' => [
                'id' => $post->id,
                'category_id' => $post->category_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'status' => $post->status?->value ?? (string) $post->status,
                'published_at' => optional($post->published_at)->toISOString(),
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'og_title' => $post->og_title,
                'og_description' => $post->og_description,
                'og_image' => $post->og_image,
                'tag_ids' => $post->tags->pluck('id')->values(),
                'deleted_at' => $post->deleted_at,
            ],
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
            'statusOptions' => array_map(fn ($s) => $s->value, PostStatus::cases()),
        ]);
    }

    public function update(UpdatePostRequest  $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $data = $request->normalized();

        $post->update($data);
        $post->tags()->sync($data['tag_ids'] ?? []);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function restore(int $post): RedirectResponse
    {
        $model = Post::withTrashed()->findOrFail($post);

        Gate::authorize('restore', $model);

        $model->restore();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post restored successfully.');
    }
}
