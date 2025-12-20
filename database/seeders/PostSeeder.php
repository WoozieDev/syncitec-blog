<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $author = $this->resolveAuthor();

        if (!$author) {
            $this->command?->warn('PostsSeeder: No hay usuarios. Crea el superadmin primero.');
            return;
        }

        $now = Carbon::now();

        // Mapa rápido de categorías por slug
        $categories = Category::query()->pluck('id', 'slug')->all();
        $tags = Tag::query()->pluck('id', 'slug')->all();

        $posts = [
            $this->post(
                title: 'Bienvenido a Syncitec Blog: stack, módulos y objetivos del MVP',
                categorySlug: 'arquitectura',
                status: 'published',
                publishedAt: $now->copy()->subDays(12),
                excerpt: 'Qué estamos construyendo, por qué elegimos Laravel 12 + Vue 3 + Inertia y cómo se organiza el proyecto por módulos.',
                content: $this->md([
                    '## ¿Qué es Syncitec Blog?',
                    'Este MVP es un blog modular orientado a **tecnología y desarrollo**, pensado para crecer sin volverse una bola de nieve.',
                    '',
                    '## Stack',
                    '- Laravel 12',
                    '- Vue 3 + Inertia 2',
                    '- Tailwind + shadcn',
                    '- RBAC (roles y permisos)',
                    '',
                    '## Objetivo',
                    'Publicar contenido técnico, mantener un panel admin limpio y permitir crecimiento por módulos sin romper el core.',
                ]),
                metaTitle: 'Syncitec Blog: stack y arquitectura modular',
                metaDescription: 'Conoce el stack (Laravel 12 + Vue 3 + Inertia) y la arquitectura modular del MVP Syncitec Blog.',
                ogTitle: 'Syncitec Blog (Laravel 12 + Vue 3 + Inertia)',
                ogDescription: 'Stack moderno y arquitectura modular para un blog escalable.',
                ogImage: null,
                tags: ['laravel-12', 'vue-3', 'inertia-2', 'modules', 'arquitectura-modular']
            ),

            $this->post(
                title: 'RBAC sin dolor: roles, permisos y policies que no se rompen en producción',
                categorySlug: 'rbac-seguridad',
                status: 'published',
                publishedAt: $now->copy()->subDays(9),
                excerpt: 'Cómo diseñamos roles/permisos (snake_case) y policies para evitar fugas de autorización en admin y frontend.',
                content: $this->md([
                    '## Problema típico',
                    'Un sistema mezcla `is_admin` con permisos sueltos y termina con inconsistencias entre backend y UI.',
                    '',
                    '## En este proyecto',
                    '- Permisos por módulo en `snake_case`',
                    '- Policies como fuente de verdad para acciones',
                    '- Superadmin con bypass controlado (sin permitir editar/borrar a otros superadmins)',
                    '',
                    '## Recomendación',
                    'Comparte permisos/roles vía Inertia para que el sidebar no “adivine”.',
                ]),
                metaTitle: 'RBAC en Laravel: roles, permisos y policies seguras',
                metaDescription: 'Guía práctica RBAC: cómo implementar roles, permisos y policies de forma consistente y segura en Laravel.',
                ogTitle: 'RBAC en Laravel (sin hacks)',
                ogDescription: 'Roles, permisos y policies bien diseñadas para un admin robusto.',
                ogImage: null,
                tags: ['rbac', 'policies', 'gates', 'spatie-permission']
            ),

            $this->post(
                title: 'Deploy en hosting: por qué falla config:cache y cómo evitarlo',
                categorySlug: 'devops-deploy',
                status: 'published',
                publishedAt: $now->copy()->subDays(6),
                excerpt: 'Checklist real de deploy: permisos, caches, autoload y seeders sin Faker.',
                content: $this->md([
                    '## Lo que más rompe',
                    '- `composer install --no-dev` + seeders/factories con Faker',
                    '- permisos en `storage/` y `bootstrap/cache`',
                    '- caches desalineados al cambiar `.env`',
                    '',
                    '## Flujo recomendado',
                    '1) `php artisan optimize:clear`',
                    '2) `php artisan config:cache`',
                    '3) `php artisan route:cache` (si aplica)',
                    '4) `php artisan view:cache`',
                    '',
                    '## Tip',
                    'En producción, si quieres data inicial “real”, usa seeders sin Faker.',
                ]),
                metaTitle: 'Deploy Laravel: errores comunes en hosting (caches y permisos)',
                metaDescription: 'Soluciona problemas típicos de deploy en hosting: config cache, permisos, autoload y seeders.',
                ogTitle: 'Deploy Laravel en hosting sin sorpresas',
                ogDescription: 'Checklist simple para que config:cache y autoload no te exploten.',
                ogImage: null,
                tags: ['deploy', 'caching', 'config-cache', 'debugging']
            ),

            $this->post(
                title: 'Vue 3 + Inertia: layouts y sidebar por permisos (sin parches)',
                categorySlug: 'vue-inertia',
                status: 'draft',
                publishedAt: null,
                excerpt: 'Patrón simple para BlogLayout/AdminLayout y un sidebar que se mantiene consistente con el backend.',
                content: $this->md([
                    '## Layouts',
                    'Separar `BlogLayout` y `AdminLayout` reduce condicionales y hace más clara la navegación.',
                    '',
                    '## Sidebar por permisos',
                    'La UI debe depender de lo que comparte el backend (roles/permisos o `can`).',
                    '',
                    '## DX',
                    '- Estados de carga',
                    '- Manejo de errores',
                    '- Componentes reutilizables con shadcn',
                ]),
                metaTitle: 'Vue 3 + Inertia: layouts y sidebar por permisos',
                metaDescription: 'Patrones recomendados para layouts y menú dinámico por permisos en Vue 3 + Inertia.',
                ogTitle: 'Layouts + permisos en Inertia',
                ogDescription: 'Navegación consistente entre backend y frontend.',
                ogImage: null,
                tags: ['vue-3', 'inertia-2', 'tailwindcss', 'shadcn-ui']
            ),

            $this->post(
                title: 'SEO mínimo viable para el MVP: lo que sí indexa',
                categorySlug: 'seo-contenido',
                status: 'scheduled',
                publishedAt: $now->copy()->addDays(3)->setTime(9, 0),
                excerpt: 'Meta title/description + Open Graph: el kit base para que tu contenido se vea bien al compartir y empiece a posicionar.',
                content: $this->md([
                    '## Lo mínimo',
                    '- `meta_title` y `meta_description` por post',
                    '- `og_title`, `og_description`, `og_image`',
                    '',
                    '## Próximos pasos',
                    '- Sitemap',
                    '- Canonical',
                    '- Schema.org (Article)',
                ]),
                metaTitle: 'SEO para blogs: checklist mínimo para MVP',
                metaDescription: 'Checklist SEO mínimo para un blog: meta title/description y Open Graph desde el día 1.',
                ogTitle: 'SEO mínimo viable (MVP)',
                ogDescription: 'Lo esencial para indexar y compartir con buena presencia.',
                ogImage: null,
                tags: ['seo', 'open-graph', 'meta-tags', 'performance']
            ),
        ];

        foreach ($posts as $p) {
            $categoryId = $categories[$p['category_slug']] ?? null;

            if (!$categoryId) {
                $this->command?->warn("PostsSeeder: categoría no encontrada: {$p['category_slug']}");
                continue;
            }

            // Reglas de coherencia status/published_at
            $publishedAt = $p['published_at'];

            if ($p['status'] === 'published' && !$publishedAt) {
                $publishedAt = $now->copy()->subDay();
            }
            if ($p['status'] === 'draft') {
                $publishedAt = null;
            }
            if ($p['status'] === 'scheduled' && !$publishedAt) {
                $publishedAt = $now->copy()->addDays(2)->setTime(9, 0);
            }

            $post = Post::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'category_id' => $categoryId,
                    'author_id' => $author->id,
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'content' => $p['content'],
                    'status' => $p['status'],
                    'published_at' => $publishedAt,

                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],

                    'og_title' => $p['og_title'],
                    'og_description' => $p['og_description'],
                    'og_image' => $p['og_image'],
                ]
            );

            // Tags: convierte slugs a ids y sincroniza
            $tagIds = collect($p['tags'])
                ->map(fn (string $slug) => $tags[Str::slug($slug)] ?? null)
                ->filter()
                ->values()
                ->all();

            $post->tags()->sync($tagIds);
        }
    }

    private function resolveAuthor(): ?User
    {
        // Si usas roles (Spatie o tu implementación):
        $superadmin = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'superadmin'))
            ->first();

        return $superadmin ?: User::query()->first();
    }

    private function post(
        string $title,
        string $categorySlug,
        string $status,
        ?Carbon $publishedAt,
        ?string $excerpt,
        ?string $content,
        ?string $metaTitle,
        ?string $metaDescription,
        ?string $ogTitle,
        ?string $ogDescription,
        ?string $ogImage,
        array $tags,
    ): array {
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category_slug' => Str::slug($categorySlug),

            'status' => $status,
            'published_at' => $publishedAt,

            'excerpt' => $excerpt,
            'content' => $content,

            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,

            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,

            'tags' => $tags,
        ];
    }

    private function md(array $lines): string
    {
        return implode("\n", $lines) . "\n";
    }
    
}
