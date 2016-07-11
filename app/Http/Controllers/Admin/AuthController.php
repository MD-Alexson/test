<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Session;

class AuthController extends Controller
{

    public function logout()
    {
        Auth::guard('admin')->logout();
        Session::flush();
        return redirect(config('app.url'));
    }
}