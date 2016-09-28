@extends('shared.app')
@section('content')
<div id="wrapper">
    <header class="header">
        <div class="header-min header-black">
            <div class="holder">
                <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a></div>
                <nav class="header-nav">
                    <a href="/">Возможности</a>
                    <a href="/plans">Тарифы</a>
                </nav>
                <div class="header-right">
                    @if(Auth::guard('backend')->check())
                    <a href="/projects" class="header-enter">Войти</a>
                    @else
                    <a href="#login" class="header-enter modal">Войти</a>
                    @endif
                    <a href="#register" class="header-reg modal">Регистрация</a>
                </div>
            </div>
        </div>
    </header>
    <section class="pay">
        <div class="holder">
            <h2>Оплата</h2>
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
                        <div class="pay-time-text">14 дней бесплатно<span style="display: none">$0</span></div>
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
                <div class="pay-form">
                    <h4>Введите данные</h4>
                    <form action="" method="post" target="_parent">
                        {{ csrf_field() }}
                        <input type="hidden" name="form_charset" id="form_charset" value="">
                        <input type="hidden" name="tovar_id" value="">
                        <input type="hidden" name="form_id" value="26705">
                        <input type="hidden" name="order_page_referer" id="order_page_referer" value="">
                        <input type="hidden" name="pay_mode" value="20">
                        <input type="hidden" name="additional_field1_name" value="partner">
                        <input type="hidden" name="additional_field1" value="{{ \Request::cookie('abc_partner') }}">
                        <input type="hidden" name="additional_field2_name" value="utm">
                        <input type="hidden" name="additional_field2" value="{{ $data['utm_string'] }}">
                        <fieldset>
                            <label class="label">Ваши имя и фамилия</label>
                            <div>
                                <input type="text" name="name" required="required" class="input" placeholder="Введите имя и фамилию" maxlength="32">
                            </div>
                            <label class="label">Ваш email</label>
                            <div>
                                <input type="email" name="email" required="required" class="input" placeholder="Введите email" maxlength="40">
                            </div>
                            <label class="label">Ваш номер телефона</label>
                            <div>
                                <input type="text" name="phone" required="required" class="input" placeholder="Введите номер телефона" maxlength="20">
                            </div>
                            <div>
                                <input name="accepted" type="checkbox" class="check" id="ch1" checked>
                                <label for="ch1" class="check-label">Я согласен с условиями предоставления услуг</label>
                            </div>
                            <div class="pay-form-link">
                                <a href="/terms" target="_blank">Пользовательское соглашение</a>
                            </div>
                            <button class="green-button">Перейти к оплате</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
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

        var actions = [
            "{{ action('Shared\AuthController@register') }}",
            "https://abckab.e-autopay.com/checkout/save_order_data.php"
        ];

        var buttons = [
            "Попробовать бесплатно 14 дней",
            "Перейти к оплате"
        ]

        $("input[name=tovar_id]").val(ids[plan][term]);

        if (plan === 1 && term === 1){
            $(".pay-form form").attr('action', actions[0]);
            $(".pay-form button.green-button").text(buttons[0]);
        } else {
            $(".pay-form form").attr('action', actions[1]);
            $(".pay-form button.green-button").text(buttons[1]);
        }
        
    }

    $(document).ready(function () {

        var plan = 1;
        var term = 1;

        @if(Request::has('plan'))
        plan = {{ Request::get('plan') }};
        @endif

        @if(Request::has('term'))
        term = {{ Request::get('term') }};
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

        $("input[name=accepted]").on('click', function(){
            var status = $(this).prop('checked');
            if(status){
                $("form .green-button").removeClass('disabled').removeAttr('disabled');
            } else {
                $("form .green-button").addClass('disabled').attr('disabled', 'disabled');
            }
        });

        $("form .green-button").on('click', function (e) {
            var accepted = $("input[name=accepted]").prop('checked');
            if (!accepted) {
                e.preventDefault();
                alert("Вы должны принять условия предоставления услуг!");
                return false;
            }
        });
    });
    $(window).load(function(){
        document.getElementById('order_page_referer').value = document.referrer;
    });
</script>
@endsection