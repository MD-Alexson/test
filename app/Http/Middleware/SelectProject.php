<?php

namespace App\Http\Middleware;

use App\Project;
use Auth;
use Closure;
use Session;

class SelectProject
{

    public function handle($request, Closure $next)
    {
        if(!Session::has('selected_project') || empty(Session::get('selected_project'))) {
            if(Auth::guard('backend')->user()->projects->count()){
                return redirect('/projects')->with('popup_info', ['Ошибка', 'Вы не выбрали проект!']);
            } else{
                return redirect('/projects')->with('popup_no_projects', "");
            }
        } else {
            $project = Project::findOrFail(Session::get('selected_project'));
            if($project->user->id !== Auth::guard('backend')->id()){
                return redirect('/projects')->with('popup_info', ['Ошибка', 'У вас нет доступа к данному проекту!']);
            }
        }
        if(!Session::has('perpage') || empty(Session::get('perpage'))) {
            Session::put('perpage', 10);
        }
        sortDefaults();
        return $next($request);
    }
}