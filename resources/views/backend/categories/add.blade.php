@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/categories">Вернуться к списку категорий</a>
            </div>
            <div class="content-title">Новая категория</div>
            <div class="add-material">
                <form action="{{ action('Backend\CategoriesController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Название </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название категории" name="name" required="required" maxlength="255" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Миниатюра <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Миниатюра категории отображается на странице со списком категорий. Рекомендуемый размер - <strong>300x184px</strong>, соотношение сторон - <strong>16:10</strong></div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div id="thumbnail_tab">
                                <a href="#popup_thumbs" class="blue-button inline-button fancybox-thumbs" style="margin-right: 10px;">Выбрать миниатюру</a>
                                <label class="white-button inline-button" for="thumbnail">Загрузить миниатюру</label>
                                <p class='thumbnail_path'></p>
                                <input type="file" name="thumbnail" id="thumbnail" style='display: none' accept="image/jpeg,image/png,image/gif">
                                <input type='hidden' name='thumbnail_select' value='{{ asset('assets/images/thumbnails/categories/1.jpg') }}'>
                                <div class="add-mat-thumbnail-wrap">
                                    <img src="{{ asset('assets/images/thumbnails/categories/1.jpg') }}" alt="image" class="add-mat-image">
                                    <div class="check-list" id='thumbnail_save_prop' style='display: none;'>
                                        <div class="check-block">
                                            <input name="thumbnail_save_prop" type="checkbox" class="check" id="thumbnail_save_prop_check">
                                            <label for="thumbnail_save_prop_check" class="check-label">Сохранять пропорции (не обрезать 16:10)</label>
                                        </div>
                                    </div>
                                    <a style="display: none;" href="javascript: removeThumbnail();" class="white-button">Удалить миниатюру</a>
                                </div>
                            </div>
                        </div>
                        <div class="add-mat-title">Настройки</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Статус</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="status">
                                    <option value="published">Опубликовано</option>
                                    <option value="draft">Черновик</option>
                                    <option value="scheduled">Запланировано на дату</option>
                                    <option value="scheduled2">Запланировано относительно даты регистрации пользователя</option>
                                </select>
                            </div>
                        </div>
                        <div class="optional" id="scheduled">
                            <div class="add-mat-left">
                                <div class="add-mat-text">Запланировано на:</div>
                            </div>
                            <div class="add-mat-right">
                                <input type="text" class="input datetime" placeholder="Дата" name="scheduled" value="{{ Request::old('scheduled') }}">
                                <input type="hidden" name="offset" value="">
                                <div class="check-block">
                                    <input name="comingsoon1" type="checkbox" class="check" id="comingsoon1">
                                    <label for="comingsoon1" class="check-label">Отображать запланированую дату публикации</label>
                                </div>
                            </div>
                        </div>
                        <div class="optional" id="scheduled2">
                            <div class="add-mat-left">
                                <div class="add-mat-text">Опубликовать через:</div>
                            </div>
                            <div class="add-mat-right">
                                <input type="number" class="input" placeholder="Число" name="sch2num" value="1" min="1">
                                <div class="select-block">
                                    <select class="styled" name="sch2type">
                                        <option value="days">Дней</option>
                                        <option value="weeks">Недель</option>
                                        <option value="months">Месяцев</option>
                                    </select>
                                </div>
                                <div class="check-block">
                                    <input name="comingsoon2" type="checkbox" class="check" id="comingsoon2">
                                    <label for="comingsoon2" class="check-label">Отображать запланированую дату публикации</label>
                                </div>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Уровни доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Если не назначить ни один уровень доступа, публикации данной категории будут доступны только Вам</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                @if($project->levels->count())
                                    @foreach($project->levels as $level)
                                    <div class="check-block">
                                        <input name="levels[{{ $level->id }}]" type="checkbox" class="check" id="lvl{{ $level->id }}">
                                        <label for="lvl{{ $level->id }}" class="check-label">{{ $level->name }}</label>
                                    </div>
                                    @endforeach
                                @else
                                <p style="color: #c00;">Уровни доступа не созданы!</p>
                                @endif
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Родительская категория</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="parent">
                                    <option value="-1">—</option>
                                    @foreach($project->categories()->where('parent', -1)->get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Краткое описание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Краткое описание, которое отображается в списке категорий</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check">
                                        <label for="excerpt_check" class="check-label">Добавить краткое описание категории</label>
                                    </div>
                                </div>
                                <div id="excerpt_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание категории" name="excerpt">{{ Request::old('excerpt') }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Текст вверху категории</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="category_html_check" type="checkbox" class="check" id="category_html_check">
                                        <label for="category_html_check" class="check-label">Добавить текст перед контентом категории</label>
                                    </div>
                                </div>
                                <div id="category_html_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст (необязательно)" name="category_html">{{ Request::old('category_html') }}</textarea>
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
                                <div id="sidebar_type">
                                @else
                                <div id="sidebar_type" style="display: none;">
                                @endif
                                    <label style="display: block; margin-bottom: 10px; cursor: pointer">
                                        <input name="sidebar_type" value="0" type="radio" class="radio" checked="checked">&nbsp;&nbsp;&nbsp;Как на главной проекта
                                    </label>
                                    <div class="clearfix"></div>
                                    <label style="display: block; cursor: pointer">
                                        <input name="sidebar_type" value="1" type="radio" class="radio">&nbsp;&nbsp;&nbsp;Другой:
                                    </label>
                                    <div class="clearfix"></div>
                                </div>
                                <div id="sidebar_html_field" style="display: none; margin-top: 20px;">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите контент для сайдбара" name="sidebar_html">{{ Request::old('sidebar_html') }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Добавить апсейл <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Например, если у пользователя неподходящий уровень доступа, ему можно показать предложение повысить тарифный план или другое сообщение</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="upsale" type="checkbox" class="check" id="ch1">
                                        <label for="ch1" class="check-label">Показывать текст для пользователей с неподходящим уровнем доступа:</label>
                                    </div>
                                </div>
                                <div style="display: none;" id="upsale">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name="upsale_text">{{ Request::old('upsale_text') }}</textarea>
                                </div>
                            </div>
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
                                        @if($project->header_dim)
                                        <?php $header_dim = "checked = 'checked'"; $dark_class = "active"; ?>
                                        @else
                                        <?php $header_dim = ""; $dark_class = ""; ?>
                                        @endif
                                        <input name="header_dim" type="checkbox" class="check" id="ch2" {{ $header_dim }}>
                                        <label for="ch2" class="check-label tooltip-holder">Затемнить фон <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В зависимости от фона, его затемнение может сделать заголовок и подзаголовок на нем более контрастными</div>">?</span></label>
                                    </div>
                                </div>
                                @if(pathTo($project->image, 'imagepath'))
                                <input type="hidden" name="parent_image" value="{{ $project->image }}">
                                <div id="selected_image">
                                    <div class="add-mat-image-wrap">
                                        <img src="{{ url(pathTo($project->image, 'imagepath')) }}" alt="image" class="add-mat-image">
                                        <div class="add-mat-image-dark {{ $dark_class }}"></div>
                                    </div>
                                    <a href="javascript: removeImage();" class='white-button'>Удалить изображение</a>
                                </div>
                                @else
                                <div id="selected_image" style="display: none;">
                                    <div class="add-mat-image-wrap">
                                        <img src="" alt="image" class="add-mat-image">
                                        <div class="add-mat-image-dark"></div>
                                    </div>
                                    <a href="javascript: removeImage();" class='white-button'>Удалить изображение</a>
                                </div>
                                @endif
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
        $("input[name=parent_image]").val('');
        $("input[name=image_select]").val('');
        $("input[name=image]").val("");
        $("p.image_path").text("");
    }
    function removeThumbnail(){
        $("input[name=thumbnail_select]").val("{{ asset('assets/images/thumbnails/categories/1.jpg') }}");
        $("#thumbnail_tab img").attr('src', "{{ asset('assets/images/thumbnails/categories/1.jpg') }}");
        $("input[name=thumbnail]").val("");
        $("p.thumbnail_path").text("");
        $("#thumbnail_tab img").show();
        $("#thumbnail_save_prop").hide();
        $("a[href='javascript: removeThumbnail();']").hide();
    }
    $(document).ready(function () {
        $("input[name=image]").on('change', function () {
            $("p.image_path").text($(this).val());
            $("input[name=parent_image]").val('');
            $("input[name=image_select]").val("");
            $("#selected_image").hide();
        });
        $("#popup_images .image_choose a").on("click", function () {
            $("input[name=parent_image]").val('');    
            $("input[name=image]").val("");
            $("p.image_path").text("");
            $("input[name=image_select]").val($(this).children("img").attr('src'));
            $("#selected_image img").attr('src', $(this).children("img").attr('src'));
            $("#selected_image").slideDown();
            $.fancybox.close();
        });
        
        $("input[name=thumbnail]").on('change', function () {
            $("p.thumbnail_path").text($(this).val());
            $("input[name=thumbnail_select]").val("");
            $("#thumbnail_tab img").hide();
            $("#thumbnail_save_prop").show();
            $("a[href='javascript: removeThumbnail();']").show();
        });
        $("#popup_thumbs .thumb_choose a").on("click", function () {
            $("#thumbnail_tab img").show();
            $("input[name=thumbnail]").val("");
            $("p.thumbnail_path").text("");
            $("input[name=thumbnail_select]").val($(this).children("img").attr('src'));
            $("#thumbnail_tab img").attr('src', $(this).children("img").attr('src'));
            if($(this).children("img").attr('src') !== "{{ asset('assets/images/thumbnails/categories/1.jpg') }}"){
                $("a[href='javascript: removeThumbnail();']").show();
            } else {
                $("a[href='javascript: removeThumbnail();']").hide();
            }
            $.fancybox.close();
        });
        
        $("input[name=header_dim]").on('change', function(){
            if($(this).prop('checked')){
                $("#selected_image .add-mat-image-dark").addClass('active');
            } else {
                $("#selected_image .add-mat-image-dark").removeClass('active');
            }
        });

        $("input[name=excerpt_check]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#excerpt_field").slideDown(200);
            } else {
                $("#excerpt_field .textarea").val("");
                $("#excerpt_field").slideUp(200);
            }
        });

        $("input[name=category_html_check]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#category_html_field").slideDown(200);
            } else {
                $("#category_html_field").slideUp(200);
                $("#category_html_field textarea").val("");
            }
        });
        $("input[name=sidebar]").on('change', function(){
            if($(this).prop('checked')){
                $("#sidebar_type").slideDown();
                if(parseInt($("input[name=sidebar_type]:checked").val()) === 1){
                    $("#sidebar_html_field").slideDown();
                }
            } else {
                $("#sidebar_type").slideUp();
                $("#sidebar_html_field").slideUp();
            }
        });
        $("input[name=sidebar_type]").on('change', function(){
            if(parseInt($(this).val()) === 1){
                $("#sidebar_html_field").slideDown();
            } else {
                $("#sidebar_html_field").slideUp();
            }
        });
        $("input[name=upsale]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#upsale").slideDown(200);
            } else {
                $("#upsale").slideUp(200);
            }
        });

        $("input[name=expire]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#expires").slideDown(200);
            } else {
                $("#expires").slideUp(200);
            }
        });
        $("select[name=status]").on('selectmenuselect', function(){
            if($(this).val() === "scheduled"){
                $("#scheduled2").slideUp(200);
                $("#scheduled").slideDown(200);
            } else if($(this).val() === "scheduled2"){
                $("#scheduled").slideUp(200);
                $("#scheduled2").slideDown(200);
            } else {
                $(".optional").slideUp(200);
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

        @if (Request::old('appearing'))
            $("select[name=appearing] option[value=1]").prop('selected', true);
            $("select[name=appearing]").selectmenu("refresh");
        @endif
        
        @if($level_id)
        $("#lvl{{ $level_id }}").prop('checked', true);
        $.uniform.update();
        @endif
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
<div id="popup_thumbs" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Выберите миниатюру</div>
    </div>
    <div class="popup-min-content" style="padding: 0px;">
        <div class="thumb_choose">
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/1.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/2.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/3.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/4.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/5.jpg') }}">
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection