<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissionsByModule = [
            'dashboard' => [
                'view' => ['View dashboard', 'Access admin dashboard overview.'],
            ],

            'posts' => [
                'view' => ['View posts', 'View posts list in admin.'],
                'create' => ['Create posts', 'Create new posts.'],
                'update' => ['Edit posts', 'Edit existing posts.'],
                'delete' => ['Delete posts', 'Delete posts.'],
                'restore' => ['Restore posts', 'Restore soft-deleted posts.'],
            ],

            'categories' => [
                'view' => ['View categories', 'View categories list.'],
                'create' => ['Create categories', 'Create new categories.'],
                'update' => ['Edit categories', 'Edit categories.'],
                'delete' => ['Delete categories', 'Delete categories.'],
                'restore' => ['Restore categories', 'Restore soft-deleted categories.'],
            ],

            'tags' => [
                'view' => ['View tags', 'View tags list.'],
                'create' => ['Create tags', 'Create new tags.'],
                'update' => ['Edit tags', 'Edit tags.'],
                'delete' => ['Delete tags', 'Delete tags.'],
                'restore' => ['Restore tags', 'Restore soft-deleted tags.'],
            ],

            'comments' => [
                'view' => ['View comments', 'View comments in admin.'],
                'moderate' => ['Moderate comments', 'Approve or reject comments.'],
                'delete' => ['Delete comments', 'Delete comments.'],
            ],

            'users' => [
                'view' => ['View users', 'View users list.'],
                'create' => ['Create users', 'Create new users.'],
                'update' => ['Edit users', 'Edit existing users.'],
                'delete' => ['Delete users', 'Soft-delete users.'],
                'restore' => ['Restore users', 'Restore soft-deleted users.'],
            ],

            'roles' => [
                'view' => ['View roles', 'View roles list.'],
                //'create' => ['Create roles', 'Create roles.'],
                'update' => ['Edit roles', 'Edit roles.'],
                //'delete' => ['Delete roles', 'Delete roles.'],
            ],

            'permissions' => [
                'view' => ['View permissions', 'View permissions list.'],
                'assign' => ['Assign permissions', 'Assign permissions to roles.'],
            ],
        ];

        // 1) Create/update permissions
        foreach ($permissionsByModule as $module => $actions) {
            foreach ($actions as $action => [$displayName, $description]) {
                $slug = "{$module}.{$action}";

                Permission::updateOrCreate(
                    ['name' => $slug],
                    [
                        'display_name' => $displayName,
                        'description' => $description,
                    ]
                );
            }
        }

        // 2) Base roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $reader = Role::firstOrCreate(['name' => 'reader']);

        // 3) Assign permissions by role
        $allIds = Permission::query()->pluck('id')->all();

        $admin->permissions()->sync($allIds);

        $editorSlugs = [
            'dashboard.view',

            'posts.view', 'posts.create', 'posts.update',
            'categories.view', 'categories.create', 'categories.update',
            'tags.view', 'tags.create', 'tags.update',

            'comments.view', 'comments.moderate',
        ];

        $editor->permissions()->sync(
            Permission::query()->whereIn('name', $editorSlugs)->pluck('id')->all()
        );

        // Reader: sin permisos de admin panel
        $reader->permissions()->sync([]);
    }
}
