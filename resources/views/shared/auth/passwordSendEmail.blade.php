@extends('shared.app')
@section('content')
<div id="wrapper">
    <header class="header">
        <div class="header-min header-black">
            <div class="holder">
                <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a></div>
                <nav class="header-nav">
                    <a href="/plans">Возможности</a>
                    <a href="/plans">Тарифы</a>
                </nav>
                <div class="header-right">
                    <a href="#login" class="header-enter modal">Войти</a>
                    <a href="#register" class="header-reg modal">Регистрация</a>
                </div>
            </div>
        </div>
    </header>
    <section class="terms">
        <div class="holder">
            <div class="pay-form" style='margin: 0px auto; width: 640px; background: none'>
                <h4>Восстановление пароля:</h4>
                <form action="{{ action('Shared\AuthController@passwordSendEmail') }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <label class="label">Введите ваш email</label>
                        <div>
                            <input type="email" name="email" required="required" class="input" placeholder="Email">
                        </div>
                        <button class="green-button">Восстановить пароль</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
    <script type='text/javascript'>
        function footer(){
            var height = $("#wrapper > header").outerHeight() + $("#wrapper > section").outerHeight() + $("#wrapper > footer").outerHeight();
            if(height < $(window).height()){
                $("#wrapper footer").addClass('fixed');
            } else {
                $("#wrapper footer").removeClass('fixed');
            }
        }
        $(window).resize(function(){
            footer();
        });
        $(document).ready(function(){
            footer();
            $("form .green-button").on('click', function (e) {
                var email = $(this).closest('form').find('input[name=email]').val();
                
                if(email === ""){
                    e.preventDefault();
                    alert("Вы должны ввести email!");
                    return false;
                }
            });
        });
    </script>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
@endsection