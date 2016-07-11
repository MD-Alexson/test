@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/projects">Вернуться к списку проектов</a>
            </div>
            <div class="content-title">Настройки проекта "{{ $project->name }}"<a href="{{ getPreviewLink('project', $project->domain) }}" target="_blank"></a></div>
            <div class="clearfix"></div>
            <div class="add-material">
                <form action="{{ action('Backend\ProjectsController@update') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Заголовок</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название проекта" name="name" required="required" maxlength="255" value="{{ $project->name }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">URL проекта</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input disabled" name="domain" maxlength="40" value="{{ $project->domain }}" disabled="disabled" required="required">
                            <label class='tooltip-holder'>Привязать свой домен <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Если у Вас уже есть свой домен или поддомен, вы можете связать его с проектом. Введите в поле ниже этот домен. Максимум 32 символа. Далее, для того чтоб связать его с проектом, необходимо в настройках домена на сайте, где вы покупали домен указать А запись и вписать в нее следующий IP адрес: <strong>{{ config('app.ip') }}</strong>, либо CNAME: <strong>{{ config('app.domain') }}</strong></div>">?</span>:</label>
                            <br/>
                            <br/>
                            <input type="text" class="input" placeholder="domainname.com" name="remote_domain" maxlength="40" value="{{ $project->remote_domain }}">
                        </div>
                        <div class="add-mat-title">Настройки сайта</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Шапка</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите подзаголовок" name="header_text" maxlength="255" value="{{ $project->header_text }}">
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text">Вход / Регистрация для пользователей</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if(!empty($project->login_html))
                                        <input name="login_html_check" type="checkbox" class="check" id="login_html" checked="checked">
                                        @else
                                        <input name="login_html_check" type="checkbox" class="check" id="login_html">
                                        @endif
                                        <label for="login_html" class="check-label tooltip-holder">Добавить сопроводительный текст на страницу входа/регистрации на сайте для пользователей <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Контент, который будут видеть пользователи на странице авторизации в проект</div>">?</span></label>
                                    </div>
                                </div>
                                @if(!empty($project->login_html))
                                <div id="login_html_field">
                                @else
                                <div id="login_html_field" style="display: none;">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='login_html'>{{ $project->login_html }}</textarea>
                                </div>
                                <div class="check-list">
                                    <div class="check-block">
                                        @if(!empty($project->deactivated_html))
                                        <input name="deactivated_html_check" type="checkbox" class="check" id="deactivated_html" checked="checked">
                                        @else
                                        <input name="deactivated_html_check" type="checkbox" class="check" id="deactivated_html">
                                        @endif
                                        <label for="deactivated_html" class="check-label tooltip-holder">Добавить сопроводительный текст для деактивированных пользователей <span class="tooltip-icon-left tooltip-icon-inline" data-content="<div class='popover-inner-text'>Контент, который будут видеть ваши неактивные пользователи на странице авторизации, например, когда закончился срок действия их аккаунта на вашем проекте</div>">?</span></label>
                                    </div>
                                </div>
                                @if(!empty($project->deactivated_html))
                                <div id="deactivated_html_field">
                                @else
                                <div id="deactivated_html_field" style="display: none;">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='deactivated_html'>{{ $project->deactivated_html }}</textarea>
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
                                <label class="white-button inline-button" for="image" style="margin-right: 20px;" style="margin-right: 20px;">Загрузить свой фон</label>
                                <p class='image_path'></p>
                                <input type="file" name="image" id="image" style='display: none' accept="image/jpeg,image/png,image/gif">
                                <input type='hidden' name='image_select' value=''>
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($project->header_dim)
                                        <?php $header_dim = "checked = 'checked'"; $dark_class = "active"; ?>
                                        @else
                                        <?php $header_dim = ""; $dark_class = ""; ?>
                                        @endif
                                        <input name="header_dim" type="checkbox" class="check" id="ch0" {{ $header_dim }}>
                                        <label for="ch0" class="check-label tooltip-holder">Затемнить фон <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В зависимости от фона, его затемнение может сделать заголовок и подзаголовок на нем более контрастными</div>">?</span></label>
                                    </div>
                                </div>
                                @if(pathTo($project->image, 'imagepath'))
                                <div id="selected_image">
                                    <div class="add-mat-image-wrap">
                                        <img src="{{ url(pathTo($project->image, 'imagepath')) }}" alt="image" class="add-mat-image">
                                        <div class="add-mat-image-dark {{ $dark_class }}"></div>
                                    </div>
                                    <a href="javascript: removeImage();" class="white-button">Удалить изображение</a>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Код</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-block">
                                    @if(!empty($project->head_end_user_code))
                                    <input name="head_end_user_code_check" type="checkbox" class="check" id="head_end_user_code" checked="checked">
                                    @else
                                    <input name="head_end_user_code_check" type="checkbox" class="check" id="head_end_user_code">
                                    @endif
                                    <label for="head_end_user_code" class="check-label tooltip-holder">Добавить код или скрипт перед &lt;/head&gt; <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Код отслеживания Яндекс Метрика или другие скрипты для всех страниц вашего проекта</div>">?</span></label>
                                </div>
                                @if(!empty($project->head_end_user_code))
                                <div id="head_end_user_code_field">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды перед закрытием &lt;/head&gt;" name="head_end_user_code">{{ $project->head_end_user_code }}</textarea>
                                </div>
                                @else
                                <div id="head_end_user_code_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды перед закрытием &lt;/head&gt;" name="head_end_user_code">{{ $project->head_end_user_code }}</textarea>
                                </div>
                                @endif
                                <div class="check-block">
                                    @if(!empty($project->body_start_user_code))
                                    <input name="body_start_user_code_check" type="checkbox" class="check" id="body_start_user_code" checked="checked">
                                    @else
                                    <input name="body_start_user_code_check" type="checkbox" class="check" id="body_start_user_code">
                                    @endif
                                    <label for="body_start_user_code" class="check-label tooltip-holder">Добавить скрипт сразу после &lt;body&gt; <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Код отслеживания Google Analytics или другие скрипты для всех страниц вашего проекта</div>">?</span></label>
                                </div>
                                @if(!empty($project->body_start_user_code))
                                <div id="body_start_user_code_field">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды после открытия &lt;body&gt;" name="body_start_user_code">{{ $project->body_start_user_code }}</textarea>
                                </div>
                                @else
                                <div id="body_start_user_code_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите коды после открытия &lt;body&gt;" name="body_start_user_code">{{ $project->body_start_user_code }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Копирайт</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($project->disable_copyright)
                                        <input name="disable_copyright" type="checkbox" class="check" id="ch1" checked="checked">
                                        @else
                                        <input name="disable_copyright" type="checkbox" class="check" id="ch1">
                                        @endif
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
                                        @if($project->sidebar)
                                        <input name="sidebar" type="checkbox" class="check" id="chside" checked="checked">
                                        @else
                                        <input name="sidebar" type="checkbox" class="check" id="chside">
                                        @endif
                                        <label for="chside" class="check-label tooltip-holder">Отображать сайдбар <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В сайдбаре (боковой панели) вы можете добавить контент, который будет отображаться на каждой странице проекта справа. Например, контакты или любую другую информацию</div>">?</span></label>
                                    </div>
                                </div>
                                @if($project->sidebar)
                                <div id="sidebar_html_field">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите контент для сайдбара" name="sidebar_html">{{ $project->sidebar_html }}</textarea>
                                </div>
                                @else
                                <div id="sidebar_html_field" style="display: none;">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите контент для сайдбара" name="sidebar_html">{{ $project->sidebar_html }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Текст на главной странице</div>
                            </div>
                            <div class="add-mat-right">
                                <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name='dashboard_html'>{{ $project->dashboard_html }}</textarea>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Футер</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-block">
                                    @if(!empty($project->vk) || !empty($project->fb) || !empty($project->tw) || !empty($project->yt) || !empty($project->insta) || !empty($project->blog))
                                    <input name="footer_check" type="checkbox" class="check" id="footer_check" checked="checked">
                                    @else
                                    <input name="footer_check" type="checkbox" class="check" id="footer_check">
                                    @endif
                                    <label for="footer_check" class="check-label tooltip-holder">Добавить ссылки на социальные сети, блог <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Ссылки будут отображаться в виде иконок внизу всех страниц вашего проекта. Необходимо указывать корректные ссылки, начинающиеся с http:// или https://</div>">?</span></label>
                                </div>
                                @if(!empty($project->vk) || !empty($project->fb) || !empty($project->tw) || !empty($project->yt) || !empty($project->insta) || !empty($project->blog))
                                <div id="footer_field">
                                @else
                                <div id="footer_field" style="display: none;">
                                @endif
                                    <input type="text" class="input" placeholder="Vkontakte" name="vk" maxlength="40" value="{{ $project->vk }}">
                                    <input type="text" class="input" placeholder="Facebook" name="fb" maxlength="40" value="{{ $project->fb }}">
                                    <input type="text" class="input" placeholder="Twitter" name="tw" maxlength="40" value="{{ $project->tw }}">
                                    <input type="text" class="input" placeholder="Youtube" name="yt" maxlength="40" value="{{ $project->yt }}">
                                    <input type="text" class="input" placeholder="Instagram" name="insta" maxlength="40" value="{{ $project->insta }}">
                                    <input type="text" class="input" placeholder="Блог" name="blog" maxlength="40" value="{{ $project->blog }}">
                                </div>
                                <div class="check-block">
                                    @if(!empty($project->custom_copyright))
                                    <input name="custom_copyright_check" type="checkbox" class="check" id="custom_copyright_check" checked="checked">
                                    @else
                                    <input name="custom_copyright_check" type="checkbox" class="check" id="custom_copyright_check">
                                    @endif
                                    <label for="custom_copyright_check" class="check-label tooltip-holder">Добавить копирайт или текстовую информацию в футере <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Здесь вы можете разместить копирайт, контактную или другую информацию, которая будет размещаться внизу на всех страницах вашего проекта</div>">?</span></label>
                                </div>
                                @if(!empty($project->custom_copyright))
                                <div id="custom_copyright_field">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите текст вашего копирайта" name='custom_copyright'>{{ $project->custom_copyright }}</textarea>
                                </div>
                                @else
                                <div id="custom_copyright_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите текст вашего копирайта" name='custom_copyright'>{{ $project->custom_copyright }}</textarea>
                                </div>
                                @endif
                            </div>
                        </div>
                        <br/>
                        <div class="add-mat-right-holder">
                            <button class="green-button float-left" type="submit">Сохранить</button>
                            <button class="white-button float-left" type="submit">Предпросмотр</button>
                            <a href="#popup_project_delete" class="grey-button float-right fancybox">Удалить проект</a>
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
        $("#input[name=image]").val("");
        $(".image_path").text("");
        $("form").append("<input type='hidden' name='image_remove' value='1'>");
        return false;
    }
    $(document).ready(function () {

        @if($project->dashboard_type)
            $("input[type=radio][value=1]").prop('checked', true);
            $.uniform.update();
        @endif

        $("#popup_images .image_choose a").on("click", function () {
            $("input[name=image]").val("");
            $(".image_path").text("");
            $("input[name=image_select]").val($(this).children("img").attr('src'));
            $("#selected_image img").attr('src', $(this).children("img").attr('src'));
            $("#selected_image").slideDown();
            $.fancybox.close();
        });

        $("input[name=image]").on('change', function () {
            $("p.image_path").text($(this).val());
            $("input[name=image_select]").val("");
            $("#selected_image").hide();
        });

        $("input[name=header_dim]").on('click', function(){
            if($(this).prop('checked')){
                $('.add-mat-image-dark').addClass('active');
            } else {
                $('.add-mat-image-dark').removeClass('active');
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
                $("#custom_copyright_field textarea").val("");
                $("#custom_copyright_field").slideUp();
            }
        });

        $(".green-button[type=submit]").on('click', function(e){
            $(".add-material form").attr('target', "");
            $("input[name=preview]").remove();
        });

        $(".white-button[type=submit]").on('click', function(e){
            $(".add-material form").attr('target', "_blank");
            $(".add-material form").append("<input type='hidden' name='preview' value='1'>");
        });
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
<div id="popup_project_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить проект?</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">При этом удалятся все материалы, категории, пользователи и файлы!</div>
        <div class='popup-min-text'>
            <form action='{{ action('Backend\ProjectsController@delete') }}' method="post" id='form_project_delete'>
                {{ csrf_field() }}
                <input style='margin: 0 auto' type="password" class="input" placeholder="Введите пароль" name="password" required="required" minlength="8" maxlength="20">
            </form>
        </div>
    </div>
    <div class="popup-min-bottom">
        <input type='submit' form="form_project_delete" class="red-button outline" value='Да'>
        <button class="green-button close">Нет</button>
    </div>
</div>
@endsection