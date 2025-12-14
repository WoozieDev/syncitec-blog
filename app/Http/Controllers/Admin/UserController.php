<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserIndexRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{

    public function index(UserIndexRequest $request): Response
    {
        Gate::authorize('viewAny', User::class);

        $filters = $request->filters();

        $users = User::query()
            ->with('roles')
            ->when($filters['trashed'] === 'with', fn ($q) => $q->withTrashed())
            ->when($filters['trashed'] === 'only', fn ($q) => $q->onlyTrashed())
            ->search($filters['search'] ?? null)
            ->withRole($filters['role'] ?? null)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = Role::orderBy('name')->pluck('name');

        return Inertia::render('admin/users/Index', [
            'title' => 'Users',
            'users' => $users,
            'filters' => $filters,
            'roleOptions' => $roles,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        $roles = Role::orderBy('name')->pluck('name');

        return Inertia::render('admin/users/Create', [
            'title' => 'Create user',
            'roleOptions' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['roles'])) {
            $roles = Role::whereIn('name', $data['roles'])->pluck('id');
            $user->roles()->sync($roles);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        $roles = Role::orderBy('name')->pluck('name');

        return Inertia::render('admin/users/Edit', [
            'title' => 'Edit user',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
            'roleOptions' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (array_key_exists('roles', $data)) {
            $roles = Role::whereIn('name', $data['roles'] ?? [])->pluck('id');
            $user->roles()->sync($roles);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
