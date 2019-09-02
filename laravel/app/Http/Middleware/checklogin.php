<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class checklogin
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

        $request->merge(array_map('htmlentities', $request->all()));


        if (Sentinel::check()) {
            return $next($request);
        } else {
            return redirect('/');
        }

    }
}
