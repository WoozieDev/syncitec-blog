<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Permission::class);

        return Inertia::render('admin/permissions/Index', [
            'permissions' => Permission::query()
                ->orderBy('name')
                ->get(),
        ]);
    }
}
