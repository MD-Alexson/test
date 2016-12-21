<?php

Route::group(['namespace' => 'Api', 'domain' => config('app.domain')],
    function() {
    Route::post('/api/payment/abc', "PaymentController@abc");
    Route::post('/api/payment/eautopay', "PaymentController@eautopay");
    Route::post('/api/payment/justclick', "PaymentController@justclick");
    Route::any('/api/payment/fondy', "PaymentController@fondy");
    Route::any('/api/payment/fondytest', function(){
        $json_string = json_encode(\Request::all());
        Mail::raw($json_string, function($message) {
            $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
            $message->to("md.alexson@gmail.com")->subject('FONDY - POST - JSON');
        });
    });
    
    Route::get('/api/payment/success', "ViewController@success");
    Route::get('/api/payment/fail', "ViewController@fail");
});