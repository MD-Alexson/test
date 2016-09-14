<div class='row'>
    <div class='col-xs-12'>
        <script src="//api.fondy.eu/static_common/v1/checkout/ipsp.js"></script>
        <div id="checkout"></div>
        <script>
            var button = $ipsp.get("button");
            button.setHost("api.fondy.eu");
            button.setProtocol("https");
            button.setMerchantId(1397120);
            button.setAmount("300", "USD", true);
            button.setResponseUrl("https://devserver.host/api/payment/fondy");
            button.addParam("lang", "ru");
            button.setRecurringState(true);
            button.addRecurringData({
                "every": 1,
                "period": "month"
            });
            var url = button.getUrl();
            $ipsp("checkout").config({
                "wrapper": "#checkout",
                "styles": {
                    "body": {
                        "overflow": "hidden",
                        "background": "transparent"
                    },
                    ".pages-checkout": {
                        "background": "transparent"
                    }
                }
            }).scope(function () {
                this.width('100%');
                this.height(480);
                this.action('resize', function (data) {
                    this.setCheckoutHeight(data.height);
                })
                this.loadUrl(url);
            });
        </script>
    </div>
</div>