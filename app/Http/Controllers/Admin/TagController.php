<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(): Response
    {
        $tags = Tag::orderBy('name')->paginate(15);

        return Inertia::render('admin/tags/Index', [
            'title' => 'Tags',
            'tags' => $tags,
        ]);
    }
}
