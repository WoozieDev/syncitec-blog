<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::with('roles')->paginate(15);

        return Inertia::render('admin/users/Index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }
}
