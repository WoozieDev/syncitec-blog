<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // puedes cambiarlo
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // cámbialo luego
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'], // puedes cambiarlo
            [
                'name' => 'Editor',
                'password' => Hash::make('password'), // cámbialo luego
            ]
        );

        $superadmin->roles()->sync(1);
        $admin->roles()->sync(2);
        $editor->roles()->sync(3);
    }
}
