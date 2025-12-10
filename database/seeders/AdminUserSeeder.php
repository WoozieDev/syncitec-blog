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
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // puedes cambiarlo
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'), // cámbialo luego
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->sync([$adminRole->id]);
    }
}
