<?php

namespace App\Http\Middleware;

use Closure;

use Sentinel;

class checkstatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $status = Sentinel::check()->status;
        $page = $request->path();
        // return $url;
        if($status==0 && $page!='changepassword')
            return redirect('/changepassword');

        elseif($status==1 && $page!='phone')
            return redirect('/phone');

        elseif($status==2 && $page!='info')
            return redirect('/info');

        elseif($status==3 && $page!='profile')
            return redirect('/profile');

        else{
          if( $status>3  && ($page=='changepassword' || $page=='phone' || $page=='info' || $page=='profile'))
            return redirect('/home');
          else
            return $next($request);
            // return redirect('/home');
        }

        // return $next($request);

    }
}
