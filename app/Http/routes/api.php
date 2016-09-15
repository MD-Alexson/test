<?php

Route::group(['namespace' => 'Api', 'domain' => config('app.domain')],
    function() {
    Route::post('/api/payment/abc', "PaymentController@abc");
    Route::post('/api/payment/eautopay', "PaymentController@eautopay");
    Route::post('/api/payment/justclick', "PaymentController@justclick");
    Route::any('/api/payment/fondy', "PaymentController@fondy");
});