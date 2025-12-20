<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Laravel', 'description' => 'Ecosistema Laravel, buenas prácticas, arquitectura y despliegue.'],
            ['name' => 'Vue & Inertia', 'description' => 'Frontend con Vue 3 + Inertia: patrones, UX y performance.'],
            ['name' => 'Arquitectura', 'description' => 'Diseño modular, límites de contexto, limpieza y escalabilidad.'],
            ['name' => 'RBAC & Seguridad', 'description' => 'Roles, permisos, policies, hardening y seguridad.'],
            ['name' => 'DevOps & Deploy', 'description' => 'CI/CD, hosting, caches, colas, logs y observabilidad.'],
            ['name' => 'Base de Datos', 'description' => 'Modelado, índices, performance, migraciones y Eloquent.'],
            ['name' => 'UI/UX', 'description' => 'Tailwind, shadcn, dark mode y experiencia de usuario.'],
            ['name' => 'SEO & Contenido', 'description' => 'SEO técnico, metadatos, rendimiento y estrategia editorial.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
