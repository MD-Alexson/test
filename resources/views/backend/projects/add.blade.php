@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/projects">Вернуться к списку проектов</a>
            </div>
            <div class="content-title">Новый проект</div>
            <div class="add-material">
                <form action="{{ action('Backend\ProjectsController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Заголовок</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название проекта" name="name" required="required" maxlength="255" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">URL проекта</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="pay-time">
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <div class="radio"><span><input name="domain_type" value="abc" type="radio" class="radio" checked="checked"></span></div>
                                    </div>
                                    <div class="pay-time-text tooltip-holder">Поддомен на {{ config('app.domain') }}<span class="tooltip-icon" data-content="<div class='popover-inner-title'>Важно!</div><div class='popover-inner-text'>Поддомен нельзя будет сменить после создания проекта</div>">?</span></div>
                                </div>
                                <div class="pay-time-item">
                                    <div class="pay-time-radio">
                                        <div class="radio"><span><input name="domain_type" value="own" type="radio" class="radio"></span></div>
                                    </div>
                                    <div class="pay-time-text tooltip-holder">Свой домен<span class="tooltip-icon" data-content="<div class='popover-inner-text'>Если у Вас уже есть свой домен или поддомен, вы можете связать его с проектом. Введите в поле ниже этот домен. Максимум 32 символа. Далее, для того чтоб связать его с проектом, необходимо в настройках домена на сайте, где вы покупали домен указать А запись и вписать в нее следующий IP адрес: <strong>{{ config('app.ip') }}</strong>, либо CNAME: <strong>{{ config('app.domain') }}</strong></div>">?</span></div>
                                </div>
                            </div>
                            <div class="domain_type optional" id="abc" style="display: block">
                                <input type="text" class="input inline-button" placeholder="Введите поддомен" name="domain" required="required" maxlength="40" value="{{ Request::old('domain') }}">
                                <span class="subdomain">http://<span style="font-weight: 700">DOMAIN</span>.{{ config('app.domain') }}</span>
                            </div>
                            <div class="domain_type optional" id="own">
                                <input type="text" class="input" placeholder="domainname.com" name="remote_domain" maxlength="40" value="{{ Request::old('remote_domain') }}">
                            </div>
                            <div class="material-note" id="info1">После сохранения поддомен нельзя будет изменить!</div>
                        </div>
                        <div class="add-mat-title">Настройки сайта</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Шапка</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите подзаголовок" name="header_text" maxlength="255" value="{{ Request::old('header_text') }}">
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text">Вход / Регистрация для пользователей</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="login_html_check" type="checkbox" class="check" id="login_html">
                                        <label for="login_html" class="check-label tooltip-holder">Добавить сопроводительный текст на страницу входа/регистрации на сайте для пользователей <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Контент, который будут видеть пользователи на странице авторизации в проект</div>">?</span></label>
                                    </div>
                                </div>
                                <div id="login_html_field" style="display: none;">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='login_html'>{{ Request::old('login_html') }}</textarea>
                                </div>
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="deactivated_html_check" type="checkbox" class="check" id="deactivated_html">
                                        <label for="deactivated_html" class="check-label tooltip-holder">Добавить сопроводительный текст для деактивированных пользователей <span class="tooltip-icon-left tooltip-icon-inline" data-content="<div class='popover-inner-text'>Контент, который будут видеть ваши неактивные пользователи на странице авторизации, например, когда закончился срок действия их аккаунта на вашем проекте</div>">?</span></label>
                                    </div>
                                </div>
                                <div id="deactivated_html_field" style="display: none;">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='deactivated_html'>Упс! Похоже что срок действия вашего тарифа истек, нужно продлить аккаунт. Свяжитесь с нами:</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Отображение контента на главной</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="pay-time">
                                    <div class="pay-time-item">
                                        <div class="pay-time-radio">
                                            <div class="radio"><span><input name="dashboard_type" value="0" type="radio" class="radio" checked="checked"></span></div>
                                        </div>
                                        <div class="pay-time-text tooltip-holder">Категории и записи</div>
                                        <br/>
                                        <br/>
                                        <div class="pay-time-radio">
                                            <div class="radio"><span><input name="dashboard_type" value="1" type="radio" class="radio"></span></div>
                                        </div>
                                        <div class="pay-time-text tooltip-holder">Все записи подряд</div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Фон шапки <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Максимальное разрешение 1920х400. Изображения более высокого разрешения будут сжиматься или обрезаться<br/><br/>Использовано места: {{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <a href="#popup_images" class="blue-button inline-button fancybox" style="margin-right: 10px;">Выбрать фон</a>
                                <label class="white-button inline-button" for="image" style="margin-right: 20px;">Загрузить свой фон</label>
                                <p class='image_path'></p>
                                <input type="file" name="image" id="image" style='display: none' accept="image/jpeg,image/png,image/gif">
                                <input type="hidden" name="image_select" value="">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="header_dim" type="checkbox" class="check" id="ch2">
                                        <label for="ch2" class="check-label tooltip-holder">Затемнить фон <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В зависимости от фона, его затемнение может сделать заголовок и подзаголовок на нем более контрастными</div>">?</span></label>
                                    </div>
                                </div>
                                <div id="selected_image" style="display: none;">
                                    <div class="add-mat-image-wrap">
                                        <img src="" alt="image" class="add-mat-image">
                                        <div class="add-mat-image-dark"></div>
                                    </div>
                                    <a href="javascript: removeImage();" class="white-button">Удалить изображение</a>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Код</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-block">
                                    <input name="head_end_user_code_check" type="checkbox" class="check" id="head_end_user_code">
                                    <label for="head_end_user_code" class="check-label tooltip-holder">Добавить код или скрипт перед &lt;/head&gt; <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Код отслеживания Яндекс Метрика или другие скрипты для всех страниц вашего проекта</div>">?</span></label>
                                </div>
                                <div id="head_end_user_code_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды перед закрытием &lt;/head&gt;" name="head_end_user_code">{{ Request::old('head_end_user_code') }}</textarea>
                                </div>
                                <div class="check-block">
                                    <input name="body_start_user_code_check" type="checkbox" class="check" id="body_start_user_code">
                                    <label for="body_start_user_code" class="check-label tooltip-holder">Добавить скрипт сразу после &lt;body&gt; <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Код отслеживания Google Analytics или другие скрипты для всех страниц вашего проекта</div>">?</span></label>
                                </div>
                                <div id="body_start_user_code_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды после открытия &lt;body&gt;" name="body_start_user_code">{{ Request::old('body_start_user_code') }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Копирайт</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="disable_copyright" type="checkbox" class="check" id="ch1">
                                        <label for="ch1" class="check-label tooltip-holder">Спрятать ссылку на ABC кабинет <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете спрятать копирайт ABC Кабинет внизу страниц вашего проекта в тарифе PRO или Бизнес</div>">?</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Боковая панель</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="sidebar" type="checkbox" class="check" id="chside">
                                        <label for="chside" class="check-label tooltip-holder">Отображать сайдбар <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В сайдбаре (боковой панели) вы можете добавить контент, который будет отображаться на каждой странице проекта справа. Например, контакты или любую другую информацию</div>">?</span></label>
                                    </div>
                                </div>
                                <div id="sidebar_html_field" style="display: none;">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите контент для сайдбара" name="sidebar_html">{{ Request::old('sidebar_html') }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Текст на главной странице</div>
                            </div>
                            <div class="add-mat-right">
                                <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='dashboard_html'>{{ Request::old('dashboard_html') }}</textarea>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Футер</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-block">
                                    <input name="footer_check" type="checkbox" class="check" id="footer_check">
                                    <label for="footer_check" class="check-label tooltip-holder">Добавить ссылки на социальные сети, блог <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Ссылки будут отображаться в виде иконок внизу всех страниц вашего проекта. Необходимо указывать корректные ссылки, начинающиеся с http:// или https://</div>">?</span></label>
                                </div>
                                <div id="footer_field" style="display: none;">
                                    <input type="text" class="input" placeholder="Vkontakte" name="vk" maxlength="40" value="{{ Request::old('vk') }}">
                                    <input type="text" class="input" placeholder="Facebook" name="fb" maxlength="40" value="{{ Request::old('fb') }}">
                                    <input type="text" class="input" placeholder="Twitter" name="tw" maxlength="40" value="{{ Request::old('tw') }}">
                                    <input type="text" class="input" placeholder="Youtube" name="yt" maxlength="40" value="{{ Request::old('yt') }}">
                                    <input type="text" class="input" placeholder="Instagram" name="insta" maxlength="40" value="{{ Request::old('insta') }}">
                                    <input type="text" class="input" placeholder="Блог" name="blog" maxlength="40" value="{{ Request::old('blog') }}">
                                </div>
                                <div class="check-block">
                                    <input name="custom_copyright_check" type="checkbox" class="check" id="custom_copyright_check">
                                    <label for="custom_copyright_check" class="check-label tooltip-holder">Добавить копирайт или текстовую информацию в футере <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Здесь вы можете разместить копирайт, контактную или другую информацию, которая будет размещаться внизу на всех страницах вашего проекта</div>">?</span></label>
                                </div>
                                <div id="custom_copyright_field" id="custom_copyright_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите текст вашего копирайта" name='custom_copyright'>{{ Request::old('custom_copyright') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="add-mat-right-holder">
                            <button class="green-button float-left" type="submit">Сохранить</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
</section>
<script type='text/javascript'>
    function removeImage(){
        $("#selected_image").hide();
        $("input[name=image_select]").val('');
        $("input[name=image]").val();
        $("p.image_path").text("");
    }
    $(document).ready(function () {
        $("input[name=domain]").keyup(function(){
            var val = $(this).val();
            var val2 = urlRusLat(val);
            if(val !== val2){
                $(this).val(val2);
            }
            if(val.length){
                $("span.subdomain span").text(val2);
            } else {
                $("span.subdomain span").text("DOMAIN");
            }
        });
        $("input[name=domain_type]").on('change', function(){
            $(".domain_type.optional").hide();
            $(".domain_type.optional input").val("").removeAttr('required');
            $("#"+$(this).val()).show();
            $("#"+$(this).val()).find('input').attr('required', 'required');
            if($(this).val() === 'abc'){
                $("#info1").slideDown();
            } else {
                $("#info1").slideUp();
            }
        });
        $("input[name=image]").on('change', function () {
            $("input[name=image_select]").val("");
            $(".image_path").text($(this).val());
            $("#selected_image").hide();
        });
        $("#popup_images .image_choose a").on("click", function () {
            $("input[name=image]").val("");
            $(".image_path").text("");
            $("input[name=image_select]").val($(this).children("img").attr('src'));
            $("#selected_image img").attr('src', $(this).children("img").attr('src'));
            $("#selected_image").slideDown();
            $.fancybox.close();
        });
        $("input[name=header_dim]").on('change', function(){
            if($(this).prop('checked')){
                $("#selected_image .add-mat-image-dark").addClass('active');
            } else {
                $("#selected_image .add-mat-image-dark").removeClass('active');
            }
        });
        $("input[name=login_html_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#login_html_field").slideDown();
            } else {
                $("#login_html_field").slideUp();
                $("#login_html_field textarea").val("");
            }
        });
        $("input[name=deactivated_html_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#deactivated_html_field").slideDown();
            } else {
                $("#deactivated_html_field").slideUp();
                $("#deactivated_html_field textarea").val("");
            }
        });
        $("input[name=head_end_user_code_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#head_end_user_code_field").slideDown();
            } else {
                $("#head_end_user_code_field textarea").val("");
                $("#head_end_user_code_field").slideUp();
            }
        });
        $("input[name=body_start_user_code_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#body_start_user_code_field").slideDown();
            } else {
                $("#body_start_user_code_field textarea").val("");
                $("#body_start_user_code_field").slideUp();
            }
        });
        $("input[name=sidebar]").on('change', function(){
            if($(this).prop('checked')){
                $("#sidebar_html_field").slideDown();
            } else {
                $("#sidebar_html_field textarea").val("");
                $("#sidebar_html_field").slideUp();
            }
        });
        $("input[name=footer_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#footer_field").slideDown();
            } else {
                $("#footer_field input").val("");
                $("#footer_field").slideUp();
            }
        });
        $("input[name=custom_copyright_check]").on('change', function(){
            if($(this).prop('checked')){
                $("#custom_copyright_field").slideDown();
            } else {
                $("#custom_copyright_field .textarea").val("");
                $("#custom_copyright_field").slideUp();
            }
        });
        @if(Request::old('sidebar'))
            $("input[name=sidebar]").prop('checked', true);
            $.uniform.update();
        @endif
        @if(Request::old('disable_copyright'))
            $("input[name=disable_copyright]").prop('checked', true);
            $.uniform.update();
        @endif
        @if(Request::old('header_dim'))
            $("input[name=header_dim]").prop('checked', true);
            $.uniform.update();
        @endif
        @if(Request::old('dashboard_type'))
            $("select[name=dashboard_type] option[value=1]").prop('selected', true);
            $("select[name=dashboard_type]").selectmenu("refresh");
        @endif
    });
</script>
<style>
    .material-table tr td, .material-table tr th {
        text-align: center;
    }
</style>
@include('backend.inc.sidebar')
<div id="popup_images" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Выберите изображение</div>
    </div>
    <div class="popup-min-content" style="padding: 0px;">
        <div class="image_choose">
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/headers/1.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/headers/2.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/headers/3.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/headers/4.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/headers/5.jpg') }}">
            </a>
        </div>
    </div>
</div>
@endsection