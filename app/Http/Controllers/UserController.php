<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function adminUsers(): JsonResponse
    {
        $users = User::orderBy('first_name', 'asc')->get();

        return response()->json($users, Response::HTTP_OK);
    }
}
