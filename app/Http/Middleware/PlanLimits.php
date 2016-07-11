<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class PlanLimits
{

    public function handle($request, Closure $next, $limitType)
    {
        if ($limitType === 'projects') {
            $current = (int) Auth::guard('backend')->user()->projects->count();
            $max     = (int) Auth::guard('backend')->user()->plan->projects;
            if($current >= $max){
                return redirect()->back()->with('popup_payment', ['Ошибка', "Вы исчерпали лимит проектов!"]);
            }
        } elseif ($limitType === 's_users') {
            $current = (int) Auth::guard('backend')->user()->susers->count();
            $max     = (int) Auth::guard('backend')->user()->plan->susers;
            if($current >= $max){
                return redirect()->back()->with('popup_payment', ['Ошибка', "Вы исчерпали лимит пользователей!"]);
            }
        } else {
            return redirect()->back()->with('popup_payment', ['Ошибка', "Неизвестная ошибка :("]);
        }
        return $next($request);
    }
}