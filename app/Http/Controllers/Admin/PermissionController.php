<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function index(): Response
    {
        $permissions = Permission::paginate(15);

        return Inertia::render('admin/permissions/Index', [
            'title' => 'Permissions',
            'permissions' => $permissions,
        ]);
    }
}
