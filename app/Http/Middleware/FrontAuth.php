<?php

namespace App\Http\Middleware;

use App\Project;
use Auth;
use Closure;
use Session;

class FrontAuth
{
    public function handle($request, Closure $next) {
        if(!empty($request->domain)){
            $domain = $request->domain;
        } else {
            $domain = getDomainByRemote();
        }
        $project = Project::findOrFail($domain);
        if(!$project->levels->count()){
            exit("Не создан ни один уровень доступа! <a href='javascript: window.close();'>Закрыть</a>");
        } elseif(Auth::guard('backend')->check() && Auth::guard('backend')->id() === $project->user->id){
            Session::put('guard', 'backend');
            if(!Auth::guard('backend')->user()->status){
                return redirect('/expired');
            }
            $higher_level = getHigherLevel($project);
            if(!Session::has('level_id') || empty(Session::get('level_id'))){
                Session::put('level_id', $higher_level->id);
            } else if(Session::has('level_id') && !empty (Session::get('level_id'))){
                if(!$project->levels()->where('id', (int) Session::get('level_id'))->count()){
                    Session::put('level_id', $higher_level->id);
                }
            }

            Session::save();

            $level_id = Session::get('level_id');

            $allowed = frontendSelectLevel($project, $level_id);

            $request->merge(['allowed' => $allowed]);
            return $next($request);
        } elseif(Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->project->domain === $project->domain){
            Session::put('guard', 'frontend');
            if(!Auth::guard('frontend')->user()->status){
                return redirect('/expired');
            }
            $level_id = Auth::guard('frontend')->user()->level->id;
            Session::put('level_id', $level_id);
            Session::save();
            $allowed = frontendSelectLevel($project, $level_id);

            $request->merge(['allowed' => $allowed]);
            return $next($request);
        } else {
            return redirect('/login');
        }
    }
}
