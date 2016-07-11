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

Route::get('/not', function(){
    foreach(User::where('status', true)->get() as $user){
        $not = new Notification();
        $not->name = "Добро пожаловать в обновленный ABC Кабинет!";
        $not->text = "Рекомендуем очистить кэш и куки сайта для корректной работы после обновления! Обращаем ваше внимание что при обновлении не переносились фоновые изображения миниатюры, и ограничения по домашним заданиям. Если у вас возникнут трудности с сервисом, вы всегда можете обратиться в нашу службу поддержки, написав нам письмо: <a href='mailto:support@abckabinet.ru'>support@abckabinet.ru</a>";
        $not->user()->associate($user);
        $not->save();
    }
});

Route::group(['middleware' => ['csrf'], 'namespace' => 'Shared', 'domain' => 'partners.'.config('app.domain')], function() {

    Route::get('/', "PartnersController@index");

});