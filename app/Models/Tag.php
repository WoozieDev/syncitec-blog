<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];    

    protected static function booted(): void
    {
        static::saving(function (Tag $tag) {
            $nameChanged = $tag->isDirty('name');
            $slugChanged = $tag->isDirty('slug');

            // Si no hay slug, lo generamos desde name
            if (blank($tag->slug)) {
                $base = Str::slug($tag->name);
                $tag->slug = static::uniqueSlug($base, $tag->id);
                return;
            }

            // Si cambia el name y el slug no fue tocado manualmente, recalculamos
            if ($nameChanged && ! $slugChanged) {
                $base = Str::slug($tag->name);
                $tag->slug = static::uniqueSlug($base, $tag->id);
            }

            // Si el slug fue editado manualmente, lo normalizamos y aseguramos unicidad
            if ($slugChanged) {
                $base = Str::slug($tag->slug);
                $tag->slug = static::uniqueSlug($base, $tag->id);
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
