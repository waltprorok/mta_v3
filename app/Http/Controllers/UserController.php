<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @return View
     */
    public function adminUsers(): View
    {
        $users = User::all();

        return view('webapp.admin.user.index', compact('users', $users));
    }
}
