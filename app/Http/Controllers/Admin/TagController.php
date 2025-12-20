<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Tag::class);

        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'trashed' => $request->string('trashed')->toString() ?: null, // null | with | only
        ];

        $tags = Tag::query()
            ->when(
                $filters['trashed'] === 'with',
                fn ($q) => $q->withTrashed()
            )
            ->when(
                $filters['trashed'] === 'only',
                fn ($q) => $q->onlyTrashed()
            )
            ->when(
                filled($filters['search']),
                fn ($q) => $q->where(function ($qq) use ($filters) {
                    $qq->where('name', 'like', '%'.$filters['search'].'%')
                       ->orWhere('slug', 'like', '%'.$filters['search'].'%');
                })
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/tags/Index', [
            'title' => 'Tags',
            'tags' => $tags,
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Tag::class);

        return Inertia::render('admin/tags/Create', [
            'title' => 'Create tag',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Tag::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'], // normalizado en modelo
        ]);

        Tag::create($data);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag): Response
    {
        Gate::authorize('update', $tag);

        return Inertia::render('admin/tags/Edit', [
            'title' => 'Edit tag',
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'deleted_at' => $tag->deleted_at,
            ],
        ]);
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        Gate::authorize('update', $tag);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'],
        ]);

        $tag->update($data);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    public function restore(int $tag): RedirectResponse
    {
        $model = Tag::withTrashed()->findOrFail($tag);

        Gate::authorize('restore', $model);

        $model->restore();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag restored successfully.');
    }
}
