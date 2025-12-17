<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommentStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        //Gate::authorize('viewAdmin'); // si no tienes este gate, cambia a: Gate::authorize('viewAny', User::class);

        $stats = [
            'posts_total' => Post::count(),
            'posts_draft' => Post::where('status', PostStatus::Draft->value)->count(),
            'posts_published' => Post::where('status', PostStatus::Published->value)->count(),
            'posts_scheduled' => Post::where('status', PostStatus::Scheduled->value)->count(),

            'comments_pending' => Comment::where('status', CommentStatus::Pending->value)->count(),
            'comments_approved' => Comment::where('status', CommentStatus::Approved->value)->count(),

            'users_total' => User::count(),
            'categories_total' => Category::count(),
            'tags_total' => Tag::count(),
        ];

        $recentPosts = Post::query()
            ->with(['category:id,name', 'author:id,name'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'status' => $p->status->value,
                'published_at' => optional($p->published_at)->toDateTimeString(),
                'category' => $p->category?->name,
                'author' => $p->author?->name,
            ]);

        $pendingComments = Comment::query()
            ->where('status', CommentStatus::Pending->value)
            ->with(['user:id,name,email', 'post:id,title,slug'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'body' => $c->body,
                'created_at' => $c->created_at->toDateTimeString(),
                'user' => ['name' => $c->user->name, 'email' => $c->user->email],
                'post' => ['title' => $c->post->title, 'slug' => $c->post->slug],
            ]);

        return Inertia::render('admin/Dashboard', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'pendingComments' => $pendingComments,
        ]);
    }
}
