<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Relationships

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Helpers para roles/permisos

    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles->whereIn('name', $roleNames)->isNotEmpty();
    }

    public function permissions()
    {
        // permisos agregados a través de los roles
        return $this->roles->loadMissing('permissions')
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->values();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->contains('name', $permissionName);
    }

    public function hasAnyPermission(array $permissionNames): bool
    {
        return $this->permissions()->whereIn('name', $permissionNames)->isNotEmpty();
    }

    // Scopes

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%");
        });
    }

    public function scopeWithRole(Builder $query, ?string $roleName): Builder
    {
        if (! $roleName) {
            return $query;
        }

        return $query->whereHas('roles', function (Builder $q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
}
