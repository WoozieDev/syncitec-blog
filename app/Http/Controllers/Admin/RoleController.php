<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        $roles = Role::with('permissions')->paginate(15);

        return Inertia::render('admin/roles/Index', [
            'title' => 'Roles',
            'roles' => $roles,
        ]);
    }
}
