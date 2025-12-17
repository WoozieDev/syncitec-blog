<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => PostStatus::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $nameChanged = $post->isDirty('title');
            $slugChanged = $post->isDirty('slug');

            if (blank($post->slug)) {
                $base = Str::slug($post->title);
                $post->slug = static::uniqueSlug($base, $post->id);
                return;
            }

            if ($nameChanged && ! $slugChanged) {
                $base = Str::slug($post->title);
                $post->slug = static::uniqueSlug($base, $post->id);
            }

            if ($slugChanged) {
                $base = Str::slug($post->slug);
                $post->slug = static::uniqueSlug($base, $post->id);
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

    // Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Scopes útiles

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', PostStatus::Scheduled);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->whereNull('deleted_at')
            ->where(function (Builder $q) {
                $q->where('status', PostStatus::Published->value)
                ->orWhere(function (Builder $qq) {
                    $qq->where('status', PostStatus::Scheduled->value)
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now());
                });
            });
    }

    public function scopePublicLatest(Builder $query): Builder
    {
        return $query->visible()
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    public function scopeVisibleBySlug(Builder $query, string $slug): Builder
    {
        return $query->visible()->where('slug', $slug);
    }

    public function scopePublicWithRelations(Builder $query): Builder
    {
        return $query->with([
            'category:id,name,slug',
            'author:id,name',
            'tags:id,name,slug',
        ]);
    }
}
