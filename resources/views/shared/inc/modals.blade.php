<div id="boxes">
    <div id="login" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                <div class="popup-title">Вход</div>
                <form action="{{ action('Shared\AuthController@login') }}" method='post' id='login_form'>
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="popup-input">
                            <input name='email' type="email" class="input" placeholder="Введите email" required="required">
                        </div>
                        <div class="popup-input">
                            <input name='password' type="password" class="input active" placeholder="Введите пароль" required="required" minlength="8" maxlength="20">
                            <input type="text" class="input" placeholder="Введите пароль" style='display: none' maxlength="20">
                            <a href="javascript: toggleLoginPass();" class="show-pass"></a>
                        </div>
                    </fieldset>
                    <div class="popup-links">
                        <a href="/login/password" class="popup-link-left">Я забыл пароль</a>
                        <a href="#register" class="popup-link-right modal">Зарегистрироваться</a>
                    </div>
                    <div class="popup-bottom">
                        <div class="check-block">
                            <input name="remember" type="checkbox" class="check" id="ch111">
                            <label for="ch111" class="check-label">Запомнить меня</label>
                        </div>
                        <div class="popup-button">
                            <button class="green-button" type='submit'>Войти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="admin_login" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                <div class="popup-title">Вход а админ. часть</div>
                <form action="{{ action('Shared\AuthController@loginAdmin') }}" method='post' id='login_form'>
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="popup-input">
                            <input name='password' type="password" class="input active" placeholder="Введите пароль" required="required" minlength="8" maxlength="20">
                        </div>
                    </fieldset>
                    <div class="popup-bottom">
                        <div class="check-block">
                            <input name="remember" type="checkbox" class="check" id="chadmin">
                            <label for="chadmin" class="check-label">Запомнить меня</label>
                        </div>
                        <div class="popup-button">
                            <button class="green-button" type='submit'>Войти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="register" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                <div class="popup-title">Регистрация</div>
                <form action="{{ action('Shared\AuthController@register') }}" method="post" id='register_form'>
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="popup-input">
                            <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                        </div>
                        <div class="popup-input">
                            <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                        </div>
                        <div class="popup-input">
                            <input name='phone' type="text" class="input" placeholder="Введите номер телефона" required="required" maxlength="20">
                        </div>
                    </fieldset>
                    <div class="popup-bottom" style='text-align: center;'>
                        <div class="popup-button" style='float: none!important; display: inline-block;'>
                            <button class="green-button">Регистрация</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="success" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                <div class="popup-title">Спасибо за регистрацию!</div>
                <p>Ожидайте письмо на ваш email с дальнейшими инструкциями!</p>
                <br/><br/>
            </div>
        </div>
    </div>
    @if(Session::has('my_modal'))
    <div id="my_modal" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                <div class="popup-title">{{ Session::get('my_modal')[0] }}</div>
                <p>{{ Session::get('my_modal')[1] }}</p>
                <br/><br/>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#mask, .window').hide();
            var id = "#my_modal";
            var maskHeight = $(document).height();
            var maskWidth = $(window).width();
            $('#mask').css({'height':maskHeight});
            $('#mask').fadeTo("slow",1);
            var winH = $(window).height();
            var winW = $(window).width();
            $(id).css('top',  winH/2-$(id).height()/2);
            $(id).css('left', winW/2-$(id).width()/2);
            $(id).fadeIn(1000);
        });
    </script>
    @endif
    @if(count($errors) > 0)
    <div id="errors" class="window">
        <div class="popup-logo-holder">
            <img src="{{ asset('assets/images/logo5.png') }}" alt="" class="popup-logo">
            <div class="popup">
                <a href="" class="close"></a>
                @if(count($errors) === 1)
                <div class="popup-title">Ошибка:</div>
                @else
                <div class="popup-title">Ошибки:</div>
                @endif
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style='list-style: circle'>{{ $error }}</li>
                    @endforeach
                </ul>
                <br/><br/>
            </div>
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#mask, .window').hide();
            var id = "#errors";
            $('#mask').fadeTo("slow",1);
            var winH = $(window).height();
            var winW = $(window).width();
            $(id).css('top',  winH/2-$(id).outerHeight()/2);
            $(id).css('left', winW/2-$(id).outerWidth()/2);
            $(id).fadeIn(1000);
        });
    </script>
    @endif
    @include('shared.inc.modals_info')
    <div id="mask"></div>
    <div id="mask2"></div>
</div>
<script type='text/javascript'>
    function toggleLoginPass() {
        if ($("#login_form input[type=password]").hasClass('active')) {
            $("#login_form input[type=password]").removeClass('active');
            $("#login_form input[type=password]").hide();
            $("#login_form input[type=text]").show();
        } else {
            $("#login_form input[type=password]").addClass('active');
            $("#login_form input[type=password]").show();
            $("#login_form input[type=text]").hide();
        }
        return false;
    }
    $(document).ready(function () {
        $("#login_form input[type=password]").on('change', function () {
            $("#login_form input[type=text]").val($(this).val());
        });
        $("#login_form input[type=text]").on('change', function () {
            $("#login_form input[type=password]").val($(this).val());
        });

    });
</script>
@if(Request::has('modal'))
<script type='text/javascript'>
    $(document).ready(function(){
        $('#mask, #mask2, .window').hide();
        var id = "#{{ Request::get('modal') }}";
        $('body').css('overflow', 'hidden');
        if(id === "#info1" || id === "#info2" || id === "#info3" || id === "#info4" || id === "#info5"){
            var mask = $("#mask2");
        } else {
            var mask = $("#mask");
            var winH = $(window).height();
            var winW = $(window).width();
            $(id).css('top',  winH/2-$(id).height()/2);
            $(id).css('left', winW/2-$(id).width()/2);
        }
        $(mask).fadeIn();
        $(id).fadeIn(500);
        if(id === "#info1" || id === "#info2" || id === "#info3" || id === "#info4" || id === "#info5"){
            function temp(){
                $(id).find('img').addClass('showed');
            }
            setTimeout(temp, 250);
        }
    });
</script>
@endif