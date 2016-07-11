@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Оплата</div>
                </div>
            </div>
            <div class="material-block">
                <section class="pay" style="padding: 0px;">
                    <div class="holder">
                        <div class="pay-left">
                            <h4>Тарифный план</h4>
                            <div class="pay-plan">
                                <div class="pay-plan-item">
                                    <div class="pay-plan-text">Тариф “Стартовый”</div>
                                    <div class="pay-plan-radio">
                                        <input name="plan" value="1" type="radio" class="radio">
                                    </div>
                                    <div class="pay-plan-right">$29/мес</div>
                                </div>
                                <div class="pay-plan-item">
                                    <div class="pay-plan-text">Тариф “Бизнес”</div>
                                    <div class="pay-plan-radio">
                                        <input name="plan" value="2" type="radio" class="radio">
                                    </div>
                                    <div class="pay-plan-right">$75/мес</div>
                                </div>
                                <div class="pay-plan-item">
                                    <div class="pay-plan-text">Тариф “PRO”</div>
                                    <div class="pay-plan-radio">
                                        <input name="plan" value="3" type="radio" class="radio">
                                    </div>
                                    <div class="pay-plan-right">$149/мес</div>
                                </div>
                            </div>
                            <h4>Период оплаты</h4>
                            <div class="pay-time pay-time1" style="display: none">
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="1_1" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">1 мес<span style="display: none">$29</span></div>
                                    <div class="pay-time-right">$29/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="1_3" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">3 мес<span style="display: none">$78</span></div>
                                    <div class="pay-time-right">$26/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="1_6" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">6 мес<span style="display: none">$132</span></div>
                                    <div class="pay-time-right">$22/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="1_12" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">12 мес<span style="display: none">$228</span></div>
                                    <div class="pay-time-right">$19/мес</div>
                                </div>
                            </div>
                            <div class="pay-time pay-time2" style="display: none">
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="2_1" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">1 мес<span style="display: none">$75</span></div>
                                    <div class="pay-time-right">$75/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="2_3" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">3 мес<span style="display: none">$201</span></div>
                                    <div class="pay-time-right">$67/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="2_6" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">6 мес<span style="display: none">$348</span></div>
                                    <div class="pay-time-right">$58/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="2_12" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">12 мес<span style="display: none">$588</span></div>
                                    <div class="pay-time-right">$49/мес</div>
                                </div>
                            </div>
                            <div class="pay-time pay-time3" style="display: none">
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="3_1" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">1 мес<span style="display: none">$149</span></div>
                                    <div class="pay-time-right">$149/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="3_3" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">3 мес<span style="display: none">$399</span></div>
                                    <div class="pay-time-right">$133/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="3_6" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">6 мес<span style="display: none">$696</span></div>
                                    <div class="pay-time-right">$116/мес</div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <input name="pay" value="3_12" type="radio" class="radio">
                                    </div>
                                    <div class="pay-time-text">12 мес<span style="display: none">$1188</span></div>
                                    <div class="pay-time-right">$99/мес</div>
                                </div>
                            </div>
                            <div class="pay-divider"></div>
                            <p class="pay-sum">Сумма к оплате<span></span></p>
                        </div>
                        <div class="pay-right">
                            <div class="pay-form" style="margin: 0px; padding: 34px 45px;">
                                <form action="http://abckab.e-autopay.com/checkout/save_order_data.php" method="post" target="_parent">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="form_charset" id="form_charset" value="">
                                    <input type="hidden" name="tovar_id" value="">
                                    <input type="hidden" name="form_id" value="26705">
                                    <input type="hidden" name="order_page_referer" id="order_page_referer" value="">
                                    <input type="hidden" name="pay_mode" value="20">
                                    <input type="hidden" name="name" value="{{ Auth::guard('backend')->user()->name }}">
                                    <input type="hidden" name="email" value="{{ Auth::guard('backend')->user()->email }}">
                                    <input type="hidden" name="phone" value="{{ Auth::guard('backend')->user()->phone }}">
                                    <button class="green-button" style="margin: 0px;">Перейти к оплате</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<script type="text/javascript">
    function update_form(plan, term){
        var ids = {
            1: {
                @foreach(\App\Invoice::where('plan_id', 1)->orderBy('term', 'asc')->get() as $plan1)
{{ $plan1->term.": ".$plan1->id."," }}
                @endforeach
},
            2: {
                @foreach(\App\Invoice::where('plan_id', 2)->orderBy('term', 'asc')->get() as $plan2)
{{ $plan2->term.": ".$plan2->id."," }}
                @endforeach
},
            3: {
                @foreach(\App\Invoice::where('plan_id', 3)->orderBy('term', 'asc')->get() as $plan3)
{{ $plan3->term.": ".$plan3->id."," }}
                @endforeach
}
        }
        $("input[name=tovar_id]").val(ids[plan][term]);
    }

    $(document).ready(function () {

        var plan = 1;
        var term = 1;

        @if(Request::has('plan'))
        plan = {{ Request::get('plan') }};
        @elseif(Session::has('payment.plan'))
        plan = {{ Session::get('payment.plan') }};
        @endif

        @if(Request::has('term'))
        term = {{ Request::get('term') }};
        @elseif(Session::has('payment.term'))
        term = {{ Session::get('payment.term') }};
        @endif

        if(plan !== 1 && plan !== 2 && plan !== 3){
            plan = 1;
        }

        if(term !== 1 && term !== 3 && term !== 6 && term !== 12){
            term = 1;
        }

        update_form(plan, term);

        $("input[name=plan][value="+plan+"]").prop('checked', true);
        $('.pay-time'+plan).show();
        $("input[name=pay][value="+plan+"_"+term+"]").prop('checked', true);
        $.uniform.update();

        var sum = $("input[name=pay][value="+plan+"_"+term+"]").closest(".pay-time-item").children(".pay-time-text").children('span').text();
        $("p.pay-sum span").text(sum);

        $("input[name=plan]").on('change', function () {
            plan = parseInt($(this).val());
            $(".pay-time").hide();
            $("input[name=pay][value="+plan+"_"+term+"]").prop('checked', true);
            $.uniform.update();
            $(".pay-time" + plan).show();
            update_form(plan, term);

            var sum = $("input[name=pay][value="+plan+"_"+term+"]").closest(".pay-time-item").children(".pay-time-text").children('span').text();
            $("p.pay-sum span").text(sum);
        });

        $("input[name=pay]").on('change', function(){
            term = $(this).val();
            term = parseInt(term.substring(2));
            update_form(plan, term);

            var sum = $(this).closest(".pay-time-item").children(".pay-time-text").children('span').text();
            $("p.pay-sum span").text(sum);
        });
    });
</script>
@endsection