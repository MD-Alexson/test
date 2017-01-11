<?php

Route::group(['namespace' => 'Api', 'domain' => config('app.domain')], function() {
    Route::post('/api/payment/abc', "PaymentController@abc");
    Route::post('/api/payment/eautopay', "PaymentController@eautopay");
    Route::post('/api/payment/justclick', "PaymentController@justclick");
    Route::any('/api/payment/fondy', "PaymentController@fondy");
    Route::any('/api/payment/fondypay', function() {
        $data['title'] = "Оплата неуспешна";
        return view('api.test')->with('data', $data);
    });
    Route::any('/api/payment/fondytest', function() {
        $json = '{"rrn":"","masked_card":"546940XXXXXX4224","sender_cell_phone":"","response_status":"success","sender_account":"","fee":"","rectoken_lifetime":"01.05.2019 00:00:00","reversal_amount":"0","settlement_amount":"0","actual_amount":"49400","order_status":"approved","response_description":"","verification_status":"","order_time":"11.01.2017 12:46:08","actual_currency":"UAH","order_id":"Order_1397559_b51F18Ch8t_1484131568","parent_order_id":"","merchant_data":"[]","tran_type":"purchase","eci":"5","settlement_date":"","payment_system":"card","rectoken":"07bd88f9d1fddffdd026f67b7ee6edca29b9cfd2","approval_code":"449906","merchant_id":"1397559","settlement_currency":"","payment_id":"34901984","product_id":"baza","currency":"UAH","card_bin":"546940","response_code":"","card_type":"MasterCard","amount":"49400","sender_email":"ihei2008@yandex.ru","signature":"889bdb961f43346b2c1bfe9e7761eb286e165abe"}';

        $url = "https://devserver.host/api/payment/fondy";
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    });

    Route::get('/api/payment/success', "ViewController@success");
    Route::get('/api/payment/fail', "ViewController@fail");
});
