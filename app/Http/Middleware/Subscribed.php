<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        if ($request->user() and !$request->user()
                ->subscribed('premium')
                ->where('ends_at', '<=', Carbon::now())) {
            return redirect('/account');
        }

        return $next($request);
    }
}
