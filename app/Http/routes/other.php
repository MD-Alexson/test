<?php

use App\Notification;
use App\User;

Route::get('/p/{part1}', function($part1){
    $partner = Request::cookie('abc_partner');
    if(!$partner || $partner !== $part1){
        return redirect(url('/p/'.$part1))->withCookie('abc_partner', $part1, 525948, null, 'abckabinet.ru');
    }
    return redirect("http://".$part1.".abckab.e-autopay.com");
});

Route::get('/p/{part1}/{part2}', function($part1, $part2){
    if(Request::has('plan')){
        Session::flash('payment.plan', Request::get('plan'));
    }
    if(Request::has('term')){
        Session::flash('payment.term', Request::get('term'));
    }
    $partner = Request::cookie('abc_partner');
    if(!$partner || $partner !== $part1){
        return redirect(url('/p/'.$part1.'/'.$part2))->withCookie('abc_partner', $part1, 525948, null, 'abckabinet.ru');
    }
    return redirect("http://".$part2.".".$part1.".abckab.e-autopay.com");
});

Route::group(['middleware' => ['csrf'], 'namespace' => 'Shared', 'domain' => 'partners.'.config('app.domain')], function() {
    Route::get('/', "PartnersController@index");
});