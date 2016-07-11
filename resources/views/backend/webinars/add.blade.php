@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/webinars">Вернуться к списку вебинаров</a>
            </div>
            <div class="content-title">Новый вебинар</div>
            <div class="add-material">
                <form action="{{ action('Backend\WebinarsController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Заголовок</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название вебинара" name="name" required="required" maxlength="255" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Подзаголовок</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Подзаголовок вебинара" name="sub" maxlength="255" value="{{ Request::old('sub') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">URL </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input inline-button" placeholder="Введите ссылку" name="url" required="required" maxlength="40" value="{{ Request::old('url') }}">
                            <span class="subdomain">http://{{ $project->domain }}.{{ config('app.domain') }}/webinar/<span style='font-weight: 700'>URL</span></span>
                        </div>
                        <div class="add-mat-title">Настройки</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Код вебинара <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вставьте код вебинара или видео с других сайтов здесь</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <textarea cols="30" rows="10" class="textarea" placeholder="Введите код вебинара" name="webinar_code">{{ Request::old('webinar_code') }}</textarea>
                        </div>
                        <div class='add-mat-left'>
                            <div class="add-mat-text">Дата и время начала</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input datetime" placeholder="Дата и время" name="date" value="{{ Request::old('date') }}" required="required">
                            <input type="hidden" name="offset" value="">
                            <div class="check-list">
                                <div class="check-block">
                                    <input name="timer" type="checkbox" class="check" id="ch3" checked>
                                    <label for="ch3" class="check-label">Отображать таймер до вебинара</label>
                                </div>
                                <div class="check-block">
                                    <input name="display_date" type="checkbox" class="check" id="ch4" checked>
                                    <label for="ch4" class="check-label">Отображать дату вебинара</label>
                                </div>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Контент под видео вебинара <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Контент, который будет виден пользователям сразу под окном вебинара</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                <div class="check-block">
                                    <input name="content_check" type="checkbox" class="check" id="content_check">
                                    <label for="content_check" class="check-label">Отображать контент под вебинаром</label>
                                </div>
                            </div>
                            <div id='content_field' style='display: none'>
                                <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст (необязательно)" name="content">{{ Request::old('content') }}</textarea>
                                <br/>
                            </div>
                            <div class="check-list">
                                <div class="check-block">
                                    <input name="comments" type="checkbox" class="check" id="comm">
                                    <label for="comm" class="check-label">Разрешить комментарии</label>
                                </div>
                            </div>
                            <label>Статус:</label>
                            <br/>
                            <br/>
                            <div class="select-block">
                                <select class="styled" name="status">
                                    <option value="1">Опубликовано</option>
                                    <option value="0">Черновик</option>
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id='optional'>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Фон шапки <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Максимальное разрешение 1920х400. Изображения более высокого разрешения будут сжиматься или обрезаться<br/><br/>Использовано места: {{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <a href="#popup_images" class="blue-button inline-button fancybox" style="margin-right: 10px;">Выбрать фон</a>
                                <label class="white-button inline-button" for="image">Загрузить свой фон</label>
                                <p class='image_path'></p>
                                <input type="file" name="image" id="image" style='display: none' accept="image/jpeg,image/png,image/gif">
                                <input type='hidden' name='image_select' value=''>
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
                                    <a href="javascript: removeImage();" class='white-button'>Удалить изображение</a>
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
        $("input[name=url]").keyup(function(){
            var val = $(this).val();
            var val2 = urlRusLat(val);
            if(val !== val2){
                $(this).val(val2);
            }
            if(val.length){
                $("span.subdomain span").text(val2);
            } else {
                $("span.subdomain span").text("URL");
            }
        });
        $("input[name=content_check]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#content_field").slideDown(200);
            } else {
                $("#content_field textarea").val("");
                $("#content_field").slideUp(200);
            }
        });
        $("input[name=image]").on('change', function () {
            $("p.image_path").text($(this).val());
            $("input[name=image_select]").val("");
            $("#selected_image").hide();
        });
        $("#popup_images .image_choose a").on("click", function () {
            $("input[name=image]").val("");
            $("p.image_path").text("");
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
        $(".datetime").datetimepicker({
            format: 'd.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate: new Date(),
            minTime: new Date(),
            defaultDate: new Date()
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;

        $("input[name=offset]").val(offset);
    });
</script>
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