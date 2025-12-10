<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users
            'view_users',
            'manage_users',

            // Roles
            'view_roles',
            'manage_roles',

            // Permissions CRUD
            'view_permissions',
            'manage_permissions',

            // Posts
            'view_posts',
            'manage_posts',
            'publish_posts',
            'schedule_posts',
            'delete_posts',

            // Categories
            'view_categories',
            'manage_categories',

            // Tags
            'view_tags',
            'manage_tags',

            // Comments
            'view_comments',
            'moderate_comments',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm], [
                'display_name' => ucwords(str_replace('_', ' ', $perm)),
            ]);
        }
    }
}
