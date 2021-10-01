<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlogAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ! $request->user()->admin == 1) {
            return redirect('/dashboard');
        }

        return $next($request);
    }

}
