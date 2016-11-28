<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Session;

class AccountController extends Controller {

    public function perpage($perpage) {
        Session::put('perpage', $perpage);
        Session::save();
    }

}
