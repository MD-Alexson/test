@extends('shared.app')
@section('content')
<div id="wrapper">
    <script src="//api.fondy.eu/static_common/v1/checkout/ipsp.js"></script>
    <script>
        var button = $ipsp.get("button");
        button.setHost("api.fondy.eu");
        button.setProtocol("https");
        button.setMerchantId(1397559);
        button.setAmount("0.1", "UAH", true);
        button.setResponseUrl("https://abckabinet.ru/api/payment/fondy");
        button.addParam("subscription_callback_url", "https://abckabinet.ru/api/payment/fondy");
        button.addParam("product_id", "baza");
        button.addParam("lang", "ru");
        button.setRecurringState(true);
        button.addRecurringData({"every": 1, "period": "day"});
        button.addParam("sender_email", "md.alexson@gmail.com");
        $(document).ready(function () {
            $("#go").attr('href', button.getUrl());
        });
    </script>
    <a href="" id="go">go</a>
</div>
@include('shared.inc.modals')
@endsection