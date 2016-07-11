<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserStatus
{

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('backend')->user()->status){
            return redirect('/account/expired');
        }
        return $next($request);
    }
}