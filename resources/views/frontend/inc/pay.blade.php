<script src="//api.fondy.eu/static_common/v1/checkout/ipsp.js"></script>
<div id="checkout"></div>
<script>
    var button = $ipsp.get("button");
    button.setHost("api.fondy.eu");
    button.setProtocol("https");
    button.setMerchantId(1397559);
    button.setAmount("500", "UAH", true);
    button.setResponseUrl("https://devserver.host/api/payment/fondy");
    button.addParam("subscription_callback_url","https://devserver.host/api/payment/fondy");
    button.addParam("lang", "ru");
    button.setRecurringState(true);
    button.addRecurringData({
        "every": 1,
        "period": "month"
    });
    var url = button.getUrl();
    console.log(url);
</script>