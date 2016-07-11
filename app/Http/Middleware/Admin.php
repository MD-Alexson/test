<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Admin
{

    public function handle($request, Closure $next)
    {
        if(!Session::has('users_filter_created_at') || Session::get('users_filter_created_at') === "") {
            Session::put('users_filter_created_at', -1);
        }
        if(!Session::has('users_filter_created_at_from')) {
            Session::put('users_filter_created_at_from', "");
        }
        if(!Session::has('users_filter_created_at_to')) {
            Session::put('users_filter_created_at_to', "");
        }
        if(!Session::has('users_filter_plan_id') || Session::get('users_filter_plan_id') === "") {
            Session::put('users_filter_plan_id', -1);
        }
        if(!Session::has('users_filter_payment_term') || Session::get('users_filter_payment_term') === "") {
            Session::put('users_filter_payment_term', -1);
        }
        if(!Session::has('users_filter_status') || Session::get('users_filter_status') === "") {
            Session::put('users_filter_status', -1);
        }

        if(!Session::has('users_order_by') || empty(Session::get('users_order_by'))) {
            Session::put('users_order_by', 'created_at');
            Session::save();
        }
        if(!Session::has('users_order') || empty(Session::get('users_order'))) {
            Session::put('users_order', 'desc');
            Session::save();
        }
        if(!Session::has('perpage') || empty(Session::get('perpage'))) {
            Session::put('perpage', 10);
            Session::save();
        }
        return $next($request);
    }
}