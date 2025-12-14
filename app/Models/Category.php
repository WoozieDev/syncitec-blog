<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            $nameChanged = $category->isDirty('name');
            $slugChanged = $category->isDirty('slug');

            // Caso 1: si no hay slug, lo generamos desde name
            if (blank($category->slug)) {
                $base = Str::slug($category->name);
                $category->slug = static::uniqueSlug($base, $category->id);
                return;
            }

            // Caso 2: si cambió el name y el slug NO fue tocado manualmente,
            // lo recalculamos para mantener consistencia
            if ($nameChanged && ! $slugChanged) {
                $base = Str::slug($category->name);
                $category->slug = static::uniqueSlug($base, $category->id);
            }

            // Caso 3: si el slug fue editado manualmente, lo normalizamos y aseguramos unicidad
            if ($slugChanged) {
                $base = Str::slug($category->slug);
                $category->slug = static::uniqueSlug($base, $category->id);
            }
        });
    }

    private static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 2;

        while (
            static::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
