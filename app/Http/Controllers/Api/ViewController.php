<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ViewController extends Controller
{

    public function success() {
        $data['title'] = "Оплата успешна";
        return view('api.success')->with('data', $data);
    }
    
    public function fail() {
        $data['title'] = "Оплата неуспешна";
        return view('api.fail')->with('data', $data);
    }
}