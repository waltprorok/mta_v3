<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function adminUsers()
    {
        $users = User::all();

        return view('webapp.admin.user.index', compact('users', $users));
    }
}
