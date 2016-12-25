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
        $json = '{"rrn":"","masked_card":"545708XXXXXX5431","sender_cell_phone":"","response_status":"success","sender_account":"","fee":"","rectoken_lifetime":"","reversal_amount":"0","settlement_amount":"0","actual_amount":"10","order_status":"approved","response_description":"","verification_status":"","order_time":"22.12.2016 19:03:00","actual_currency":"UAH","order_id":"recurring__1482426180.48__Order_1397559_MDZeZt1XzD_1482339679","parent_order_id":"Order_1397559_MDZeZt1XzD_1482339679","merchant_data":"","tran_type":"purchase","eci":"","settlement_date":"","payment_system":"card","rectoken":"04d83c3c3415c1256d1cd31c3f0bebf9d5b4","approval_code":"04609B","merchant_id":1397559,"settlement_currency":"","payment_id":33325055,"product_id":"","currency":"UAH","card_bin":545708,"response_code":"","card_type":"MasterCard","amount":"10","sender_email":"md.alexson@gmail.com","signature":"9932b7149d545e7c69008b83294da2dc632e674b"}';

        $url = "https://devserver.host/api/payment/fondy";
        $content = json_decode($json);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $response = curl_exec($curl);
        curl_close($curl);
        dd($response);
    });

    Route::get('/api/payment/success', "ViewController@success");
    Route::get('/api/payment/fail', "ViewController@fail");
});
