<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'laravel-12', 'vue-3', 'inertia-2', 'tailwindcss', 'shadcn-ui',
            'rbac', 'spatie-permission', 'policies', 'gates',
            'modules', 'arquitectura-modular', 'clean-code',
            'migraciones', 'eloquent', 'indices', 'performance',
            'seo', 'open-graph', 'sitemap', 'meta-tags',
            'queues', 'horizon', 'redis',
            'deploy', 'caching', 'config-cache', 'debugging',
        ];

        foreach ($tags as $name) {
            Tag::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
