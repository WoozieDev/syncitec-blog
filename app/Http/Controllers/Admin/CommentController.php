<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CommentController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Comment::class);

        $status = $request->string('status')->toString(); // pending|approved|rejected|all
        $q = $request->string('q')->toString();

        $comments = Comment::query()
            ->with([
                'user:id,name,email',
                'post:id,title,slug',
            ])
            ->when($status && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('body', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
                        ->orWhereHas('post', fn ($p) => $p->where('title', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/comments/Index', [
            'title' => 'Comments',
            'filters' => [
                'status' => $status ?: 'pending',
                'q' => $q,
            ],
            'comments' => $comments,
            'statusOptions' => [
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'approved', 'label' => 'Approved'],
                ['value' => 'rejected', 'label' => 'Rejected'],
                ['value' => 'all', 'label' => 'All'],
            ],
        ]);
    }

    public function approve(Request $request, Comment $comment): RedirectResponse
    {
        Gate::authorize('update', $comment);

        $comment->update([
            'status' => CommentStatus::Approved->value,
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Comment approved.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        Gate::authorize('update', $comment);

        $comment->update([
            'status' => CommentStatus::Rejected->value,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return back()->with('success', 'Comment rejected.');
    }
}
