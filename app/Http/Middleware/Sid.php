<?php
namespace App\Http\Middleware;
use Closure;
class Sid {
    public function handle($request, Closure $next) {
        if($request->has('sid')){
            $project = \App\Project::findOrFail($request->domain);
            $sess = \App\Session::findOrFail($request->sid);
            $payload = unserialize(base64_decode($sess->payload));
            \Session::flush();
            foreach($payload as $key => $val){
                \Session::put($key, $val);
            }
            \Session::save();
            $url = preg_replace('/\?.*/', '', \Request::fullUrl());
            return redirect($url);
        }
        return $next($request);
    }
}