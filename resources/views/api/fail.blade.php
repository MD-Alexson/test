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
        <div class="holder" style="text-align: center">
            <h1 style="color: #a00">Ошибка оплаты :(</h1>
            <br/><br/>
            <img src="{{ asset('assets/images/add-payment.png') }}">
            <br/><br/>
            <p>Свяжитесь с службой поддержки<br/><a href="javascript: history.back();">Назад</a></p>
            <br/><br/>
        </div>
    </section>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
@endsection