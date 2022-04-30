<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParentController extends Controller
{
    /**
     * @return View
     */
    public function household(): View
    {
        $parent = User::with('parentOfStudent')->findOrFail(Auth::id()); // uses pivot table

        return view('webapp.parent.household')->with('parent', $parent);
    }
}
