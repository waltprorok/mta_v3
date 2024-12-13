<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect a user after login.
     *
     * @return string
     */
    public function redirectTo(): string
    {
        /** @var User $user */
        $user = Auth::user();

        switch (true) {
            case $user->isAdmin();
                return '/admin/blog';
            case $user->isTeacher();
                return '/dashboard';
            case $user->isStudent();
                return '/calendar/student';
            case $user->isParent();
                return '/household';
            default:
                return 'dashboard';
        }
    }
}
