<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function adminUsers()
    {
        $users = User::all();
        return view('webapp.user.adminUsers', compact('users', $users));
    }
}
