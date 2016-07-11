<?php

namespace App\Http\Middleware;

use App\Project;
use Auth;
use Closure;

class Ownership
{

    public function handle($request, Closure $next)
    {
        $project = Project::findOrFail($request->project);
        if($project->user->id !== Auth::guard('backend')->id()){
            return redirect('/projects')->with('modal_info', ['Ошибка!', 'Вам не туда!']);
        }
        return $next($request);
    }
}