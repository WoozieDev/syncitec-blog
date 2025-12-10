<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Inertia\Inertia;
use Inertia\Response;

class CommentController extends Controller
{
    public function index(): Response
    {
        $comments = Comment::with(['post', 'author'])
            ->latest('created_at')
            ->paginate(15);

        return Inertia::render('admin/comments/Index', [
            'title' => 'Comments',
            'comments' => $comments,
        ]);
    }
}
