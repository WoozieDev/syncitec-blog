<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatus;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::query()->visibleBySlug($slug)->firstOrFail();

        $data = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'status' => CommentStatus::Pending->value,
        ]);

        return back()->with('success', 'Comment submitted and awaiting moderation.');
    }
}
