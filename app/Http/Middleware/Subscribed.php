<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ! $request->user()->subscribed('premium'))
        {
            return redirect('/account/subscription')->with('info', 'Please subscribe to enjoy all the benefits of Music Teachers Aid.');
        }

        return $next($request);
    }
}
