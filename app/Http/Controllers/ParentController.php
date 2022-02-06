<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function household()
    {
        $parent = User::with('parentOfStudent')->findOrFail(Auth::id()); // uses pivot table

        return view('webapp.parent.household')->with('parent', $parent);
    }
}
