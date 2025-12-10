<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        $posts = Post::with(['author', 'categories', 'tags'])
            ->latest('created_at')
            ->paginate(15);

        return Inertia::render('admin/posts/Index', [
            'title' => 'Posts',
            'posts' => $posts,
        ]);
    }
}
