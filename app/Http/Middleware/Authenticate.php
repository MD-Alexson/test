<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                switch ($guard) {
                    case "backend":
                        return redirect("/?modal=login");
                        break;
                    case "frontend":
                        return 'not yet';
                        break;
                    case "admin":
                        return redirect(config('app.url')."/?modal=admin_login");
                        break;
                    default:
                        return redirect('/?modal=login');
                }
            }
        }
        if($guard === "backend"){
            if(Auth::guard('backend')->user()->projects->count() && (!Session::has('selected_project') || empty(Session::get('selected_project')))){
                Session::put('selected_project', Auth::guard('backend')->user()->projects()->orderBy('created_at', 'desc')->first()->domain);
                Session::save();
            } elseif(!Auth::guard('backend')->user()->projects->count() && Session::has('selected_project')) {
                Session::forget('selected_project');
            }
        }

        return $next($request);
    }
}
