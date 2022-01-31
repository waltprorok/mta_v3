<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Household
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! $request->user()->parent) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
