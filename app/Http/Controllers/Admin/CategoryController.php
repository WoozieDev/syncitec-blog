<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Category::class);

        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'trashed' => $request->string('trashed')->toString() ?: null, // null | with | only
        ];

        $categories = Category::query()
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

        return Inertia::render('admin/categories/Index', [
            'title' => 'Categories',
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Category::class);

        return Inertia::render('admin/categories/Create', [
            'title' => 'Create category',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'], // lo normalizamos en el modelo
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): Response
    {
        Gate::authorize('update', $category);

        return Inertia::render('admin/categories/Edit', [
            'title' => 'Edit category',
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'deleted_at' => $category->deleted_at,
            ],
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function restore(int $category): RedirectResponse
    {
        $model = Category::withTrashed()->findOrFail($category);

        Gate::authorize('restore', $model);

        $model->restore();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category restored successfully.');
    }
}
