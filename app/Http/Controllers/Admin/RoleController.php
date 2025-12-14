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

        // Cargamos permisos del rol
        $role->load('permissions:id,name');

        $permissions = Permission::query()
            ->orderBy('name')
            ->get(['name'])
            ->pluck('name')
            ->values();

        return Inertia::render('admin/roles/Edit', [
            'title' => 'Edit role',
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->values(),
            ],
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $names = $data['permissions'] ?? [];

        $permissionIds = Permission::query()
            ->whereIn('name', $names)
            ->pluck('id')
            ->values();

        $role->permissions()->sync($permissionIds);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role permissions updated successfully.');
    }
}
