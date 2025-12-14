<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Middleware;

use function Pest\Laravel\session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = Str::of(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),

            'name' => config('app.name'),

            'quote' => [
                'message' => trim($message),
                'author' => trim($author),
            ],

            'auth' => $this->sharedAuth($request),

            'flash' => $this->sharedFlash($request),

            'sidebarOpen' => ! $request->hasCookie('sidebar_state')
                || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Shared auth data for Inertia.
     */
    private function sharedAuth(Request $request): array
    {
        $user = $request->user();

        if (! $user) {
            return [
                'user' => null,
                'roles' => [],
                'permissions' => [],
                'can' => [],
            ];
        }

        $roles = $user->roles()->pluck('name')->values()->all();
        $permissions = $user->permissions()->pluck('name')->values()->all();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ?? null,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
            'can' => collect($permissions)->mapWithKeys(
                fn (string $name) => [$name => true]
            )->all(),
        ];
    }

    /**
     * Shared flash messages.
     */
    private function sharedFlash(Request $request): array
    {
        return [
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
        ];
    }
}
