<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;

class PartnersController extends Controller
{

    public function index()
    {
        $data['title'] = " / Партнерская программа";
        return view('shared.partners')->with('data', $data);
    }
}