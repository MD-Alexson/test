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
            <h2>Контакты</h2>
            <div class="pay-left contacts">
                <h4>Контактный телефон</h4>
                <span>+7 499 322-90-96</span><br/><br/>
                <span>Время работы:</span><br/>
                <span>Пн-Пт. 10:00 - 18:00</span>
                <div class="divider"></div>
                <h4>Техническая поддержка</h4>
                <a href="mailto:support@abckabinet.ru">support@abckabinet.ru</a>
                <div class="divider"></div>
                <h4>Отдел маркетинга</h4>
                <a href="mailto:ym@abckabinet.ru">ym@abckabinet.ru</a>
                <div class="divider"></div>
            </div>
            <div class="pay-right">
                <div class="pay-form">
                    <h4>Отправить сообщение</h4>
                    <form action="{{ action("Shared\SharedController@send") }}" method="post">
                        {{ csrf_field() }}
                        <fieldset>
                            <label class="label">Ваше имя</label>
                            <div>
                                <input type="text" name="name" required="required" class="input" placeholder="Введите имя и фамилию" maxlength="32">
                            </div>
                            <label class="label">Ваш email</label>
                            <div>
                                <input type="email" name="email" required="required" class="input" placeholder="Введите email" maxlength="40">
                            </div>
                            <label class="label">Ваше сообщение</label>
                            <div>
                                <textarea class="textarea" name="message" placeholder="Введите ваше сообщение" placeholder="Введите номер телефона" maxlength="1024" style="border: 0px;" required="required"></textarea>
                            </div>
                            <button class="green-button">Отправить сообщение</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
@endsection