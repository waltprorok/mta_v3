<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! $request->user()->is_active) {
            return redirect('/')
                ->with('info', 'Your account is no longer active.');
        }

        return $next($request);
    }
}
