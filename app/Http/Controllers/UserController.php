<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function adminUsers(): AnonymousResourceCollection
    {
        $users = User::orderBy('first_name')->get();

        return UserResource::collection($users);
    }
}
