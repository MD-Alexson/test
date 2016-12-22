<?php

Route::group(['namespace' => 'Api', 'domain' => config('app.domain')],
    function() {
    Route::post('/api/payment/abc', "PaymentController@abc");
    Route::post('/api/payment/eautopay', "PaymentController@eautopay");
    Route::post('/api/payment/justclick', "PaymentController@justclick");
    Route::any('/api/payment/fondy', "PaymentController@fondy");
    Route::any('/api/payment/fondypay', function(){
        $data['title'] = "Оплата неуспешна";
        return view('api.test')->with('data', $data);
    });
    Route::any('/api/payment/fondytest', function(){
        $suser = \App\Suser::findOrFail(10272);
        $suser->status = 1;
        $suser->save();
    });
    
    Route::get('/api/payment/success', "ViewController@success");
    Route::get('/api/payment/fail', "ViewController@fail");
});