<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Ajusta estos checks según tu relación roles()
        $roleNames = $user?->roles()->pluck('name')->all() ?? [];

        $isAdmin = in_array('admin', $roleNames, true);
        $isEditor = in_array('editor', $roleNames, true);

        $target = ($isAdmin || $isEditor) ? '/admin' : '/';

        return redirect()->intended($target);
    }
}
