<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::query()
            ->withCount('permissions')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/roles/Index', [
            'title' => 'Roles',
            'roles' => $roles,
        ]);
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);

        $permissions = Permission::query()
            ->orderBy('name')
            ->get(['id', 'name', 'display_name', 'description']);

        return Inertia::render('admin/roles/Edit', [
            'title' => 'Edit role',
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
            ],
            'permissions' => $permissions,
            'assignedPermissionIds' => $role->permissions()->pluck('permissions.id')->all(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        Gate::authorize('update', $role);

        $data = $request->validate([
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->permissions()->sync($data['permission_ids'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role permissions updated.');
    }
}