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
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->isTeacher() && (now() < $request->user()->trial_ends_at)) {
            return $next($request);
        }

        if ($request->user() && $request->user()->isTeacher() && (now() > $request->user()->trial_ends_at) && ! $request->user()->subscribed('premium')) {
            return redirect('/account/subscription')
                ->with('info', 'Please subscribe to enjoy all the benefits of Music Teachers Aid.');
        }

        return $next($request);
    }
}
