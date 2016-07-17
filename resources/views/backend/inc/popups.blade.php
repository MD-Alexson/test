@if(Session::has('popup_ok'))
<div id="popup_ok" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-big-icon"><img src="{{ asset('assets/images/ok.png') }}" alt=""></div>
        <div class="popup-min-title">{{ Session::get('popup_ok')[0] }}</div>
        <div class="popup-min-text">{{ Session::get('popup_ok')[1] }}</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_ok");
    });
</script>
@endif
@if(Session::has('popup_payment'))
<div id="popup_payment" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-min-title">{{ Session::get('popup_payment')[0] }}</div>
        <div class="popup-min-text">{{ Session::get('popup_payment')[1] }}</div>
    </div>
    <div class="popup-min-bottom">
        <a href="/account/plans" class="green-button">Повысить тариф</a>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_payment");
    });
</script>
@endif
@if(Session::has('popup_info'))
<div id="popup_info" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-min-title">{{ Session::get('popup_info')[0] }}</div>
        <div class="popup-min-text">{{ Session::get('popup_info')[1] }}</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_info");
    });
</script>
@endif
@if(Session::has('popup_no_projects'))
<div id="popup_no_projects" class="popup-min">
    <div class="popup-min-content" style='padding: 22px 0px 22px 0px;'>
        <div class="popup-min-title">Создайте ваш первый проект</div>
    </div>
    <div class="popup-min-bottom">
        <a href='/projects/add' class="green-button">Создать проект</a>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_no_projects");
    });
</script>
@endif
@if(count($errors) > 0)
<div id="popup_errors" class="popup-min">
    <div class="popup-min-content">
        @if(count($errors) === 1)
        <div class="popup-min-title">Ошибка:</div>
        @else
        <div class="popup-min-title">Ошибки:</div>
        @endif
        <div class="popup-min-text">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_errors");
    });
</script>
@endif
<div id="popup_ajax_errors" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-min-title"></div>
        <div class="popup-min-text">
            <ul></ul>
        </div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<div id="popup_message" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Напишите нам</div>
    </div>
    <div class="popup-min-content">
        <div class='popup-min-text'>
            <form action='{{ action('Backend\AccountController@message') }}' method="post" id='form_message'>
                {{ csrf_field() }}
                <input style='margin: 0 auto' type="text" class="input" placeholder="Тема сообщения" name="subject" required="required" maxlength="40">
                <br/>
                <textarea style="margin: 0 auto" class="textarea" placeholder="Введите ваше сообщение" name="message" rows="5" required="required" maxlength="2048"></textarea>
            </form>
        </div>
    </div>
    <div class="popup-min-bottom">
        <input type='submit' form="form_message" class="green-button" value='Отправить'>
        <button class="white-button close">Отмена</button>
    </div>
</div>
<div id='popup_not' class='popup-min'>
    <div class="popup-min-top">
        <div class="popup-min-title"></div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text" style='text-align: left; padding: 0px 30px;'></div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline close">Удалить</a>
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<div id="loading_screen"><span>Загрузка</span></div>