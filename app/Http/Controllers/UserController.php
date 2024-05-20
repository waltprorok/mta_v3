<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function adminUsers()
    {
        return User::query()
            ->select('id',
                'first_name',
                'last_name',
                'email',
                'admin',
                'teacher',
                'student',
                'parent',
                'created_at')
            ->orderBy('first_name')
            ->get();
    }
}
