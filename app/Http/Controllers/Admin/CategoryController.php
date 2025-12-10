<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::orderBy('name')->paginate(15);

        return Inertia::render('admin/categories/Index', [
            'title' => 'Categories',
            'categories' => $categories,
        ]);
    }
}
