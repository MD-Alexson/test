<?php

namespace App\Http\Middleware;

use App\Project;
use Closure;
use Request;
use Route;

class RemoteDomain {

    public function handle($request, Closure $next) {
        $temp = Project::where('remote_domain', Request::getHost())->select('domain')->first();
        if(empty($temp)){
            abort(404);
        }
        $local = $temp->domain;

        $route = Route::getCurrentRoute();
        $params = $route->parameters();
        foreach($params as $key => $val){
            $route->forgetParameter($key);
        }
        $route->setParameter('domain', $local);
        foreach($params as $key => $val){
            $route->setParameter($key, $val);
        }

        return $next($request);
    }

}