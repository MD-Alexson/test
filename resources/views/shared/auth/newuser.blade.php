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
                <h4>Здравствуйте, {{ $user->name }}! Создайте ваш пароль:</h4>
                <form action="{{ action('Shared\AuthController@newUser', ['key' => $key]) }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <label class="label">Введите пароль</label>
                        <div>
                            <input type="password" name="password" required="required" class="input" placeholder="От 8 до 20 символов" minlength="8" maxlength="20">
                        </div>
                        <label class="label">Введите пароль повторно</label>
                        <div>
                            <input type="password" name="password_check" required="required" class="input" placeholder="От 8 до 20 символов" minlength="8" maxlength="20">
                        </div>
                        <div class="check-block">
                            <input name="accepted" type="checkbox" class="check" id="ch1" checked>
                            <label for="ch1" class="check-label">Я согласен с условиями предоставления услуг</label>
                        </div>
                        <div class="pay-form-link">
                            <a href="/terms" target="_blank">Пользовательское соглашение</a>
                        </div>
                        <br/>
                        <p><strong>Внимание!</strong> После создания пароля данная ссылка станет недействительна!</p>
                        <button class="green-button">Перейти в кабинет</button>
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
            $("input[name=accepted]").on('click', function(){
                var status = $(this).prop('checked');
                if(status){
                    $("form .green-button").removeClass('disabled').removeAttr('disabled');
                } else {
                    $("form .green-button").addClass('disabled').attr('disabled', 'disabled');
                }
            });

            $("form .green-button").on('click', function (e) {
                var pass = $(this).closest('form').find('input[name=password]').val();
                var pass_check = $(this).closest('form').find('input[name=password_check]').val();

                if(pass === "" || pass_check === ""){
                    e.preventDefault();
                    alert("Вы должны заполнить оба поля! Введите в них желаемый пароль");
                    return false;
                }

                if(pass.length < 8 || pass.length > 20 || pass_check.length < 8 || pass_check.length > 20){
                    e.preventDefault();
                    alert("Пароль должен быть длиной от 8 до 20 символов!");
                    return false;
                }

                if(pass !== pass_check){
                    e.preventDefault();
                    alert("Пароли не совпадают!");
                    return false;
                }
                
                var accepted = $("input[name=accepted]").prop('checked');
                if (!accepted) {
                    e.preventDefault();
                    alert("Вы должны принять условия предоставления услуг!");
                    return false;
                }
            });
        });
    </script>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
@endsection