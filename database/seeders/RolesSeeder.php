<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exists
        $admin = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrator'
        ]);

        $editor = Role::firstOrCreate(['name' => 'editor'], [
            'display_name' => 'Editor'
        ]);

        $reader = Role::firstOrCreate(['name' => 'reader'], [
            'display_name' => 'Reader'
        ]);

        // Assign permissions

        $allPermissions = Permission::all()->pluck('id');

        // Admin → all permissions
        $admin->permissions()->sync($allPermissions);

        // Editor → only content management permissions
        $editorPermissions = Permission::whereIn('name', [
            'view_posts',
            'manage_posts',
            'publish_posts',
            'schedule_posts',
            'delete_posts',

            'view_categories',
            'manage_categories',

            'view_tags',
            'manage_tags',

            'view_comments',
            'moderate_comments',
        ])->pluck('id');

        $editor->permissions()->sync($editorPermissions);

        // Reader → no permissions (base front-user)
        $reader->permissions()->sync([]);
    }
}
