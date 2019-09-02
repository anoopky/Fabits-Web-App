<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class checkguest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(array_map('trim', $request->all()));
        if (!Sentinel::check()) {
            return $next($request);
        } else {
            return redirect('/home');
        }

    }
}
