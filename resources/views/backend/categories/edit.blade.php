@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/categories">Вернуться к списку категорий</a>
            </div>
            <div class="content-title">Редактировать категорию "{{ $category->name }}"<a href="{{ getPreviewLink('category', $category->id) }}" target="_blank"></a></div>
            <div class="add-material">
                <form action="{{ action('Backend\CategoriesController@update', ['cat_id' => $category->id]) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Название </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название категории" name="name" required="required" maxlength="255" value="{{ $category->name }}">
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
                                <input type="text" class="input datetime" placeholder="Дата" name="scheduled" value="">
                                <input type="hidden" name="offset" value="">
                                <div class="check-block">
                                    @if($category->comingsoon)
                                    <input name="comingsoon1" type="checkbox" class="check" id="comingsoon1" checked="checked">
                                    @else
                                    <input name="comingsoon1" type="checkbox" class="check" id="comingsoon1">
                                    @endif
                                    <label for="comingsoon1" class="check-label">Отображать запланированую дату публикации</label>
                                </div>
                            </div>
                        </div>
                        <div class="optional" id="scheduled2">
                            <div class="add-mat-left">
                                <div class="add-mat-text">Опубликовать через:</div>
                            </div>
                            <div class="add-mat-right">
                                <input type="number" class="input" placeholder="Число" name="sch2num" value="{{ $category->sch2num }}" min="1">
                                <div class="select-block">
                                    <select class="styled" name="sch2type">
                                        <option value="days">Дней</option>
                                        <option value="weeks">Недель</option>
                                        <option value="months">Месяцев</option>
                                    </select>
                                </div>
                                <div class="check-block">
                                    @if($category->comingsoon)
                                    <input name="comingsoon2" type="checkbox" class="check" id="comingsoon2" checked="checked">
                                    @else
                                    <input name="comingsoon2" type="checkbox" class="check" id="comingsoon2">
                                    @endif
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
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Краткое описание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Краткое описание, которое отображается в списке категорий</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if(!empty($category->excerpt))
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check" checked="checked">
                                        @else
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check">
                                        @endif
                                        <label for="excerpt_check" class="check-label">Добавить краткое описание категории</label>
                                    </div>
                                </div>
                                @if(!empty($category->excerpt))
                                <div id="excerpt_field">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание категории (необязательно)" name="excerpt">{{ $category->excerpt }}</textarea>
                                </div>
                                @else
                                <div id="excerpt_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание категории (необязательно)" name="excerpt">{{ $category->excerpt }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Текст вверху категории</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if(!empty($category->category_html))
                                        <input name="category_html_check" type="checkbox" class="check" id="category_html_check" checked="checked">
                                        @else
                                        <input name="category_html_check" type="checkbox" class="check" id="category_html_check">
                                        @endif
                                        <label for="category_html_check" class="check-label">Добавить текст перед контентом категории</label>
                                    </div>
                                </div>
                                @if(!empty($category->category_html))
                                <div id="category_html_field">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name="category_html">{{ $category->category_html }}</textarea>
                                </div>
                                @else
                                <div id="category_html_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name="category_html">{{ $category->category_html }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Добавить апсейл <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Например, если у пользователя неподходящий уровень доступа, ему можно показать предложение повысить тарифный план или другое сообщение</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($category->upsale)
                                        <input name="upsale" type="checkbox" class="check" id="ch1" checked="checked">
                                        @else
                                        <input name="upsale" type="checkbox" class="check" id="ch1">
                                        @endif
                                        <label for="ch1" class="check-label">Показывать текст для пользователей с неподходящим уровнем доступа:</label>
                                    </div>
                                </div>
                                @if($category->upsale)
                                <div id="upsale">
                                @else
                                <div style="display: none;" id="upsale">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name="upsale_text">{{ $category->upsale_text }}</textarea>
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
                                        @if($category->header_dim)
                                        <?php $header_dim = "checked = 'checked'"; $dark_class = "active"; ?>
                                        @else
                                        <?php $header_dim = ""; $dark_class = ""; ?>
                                        @endif
                                        <input name="header_dim" type="checkbox" class="check" id="ch0" {{ $header_dim }}>
                                        <label for="ch0" class="check-label tooltip-holder">Затемнить фон <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В зависимости от фона, его затемнение может сделать заголовок и подзаголовок на нем более контрастными</div>">?</span></label>
                                    </div>
                                </div>
                                @if(pathTo($category->image, 'imagepath'))
                                <div id="selected_image">
                                    <div class="add-mat-image-wrap">
                                        <img src="{{ url(pathTo($category->image, 'imagepath')) }}" alt="image" class="add-mat-image">
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
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Миниатюра <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Миниатюра категории отображается на странице со списком категорий</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="select-block inline-button" style="margin-right: 10px;">
                                    <select class="styled" name="thumbnail_size">
                                        <option value="0">Маленькая (128px)</option>
                                        <option value="1">Большая (750px)</option>
                                    </select>
                                </div>
                                <div id="thumbnail_128_tab">
                                    <a href="#popup_thumbs" class="blue-button inline-button fancybox" style="margin-right: 10px;">Выбрать миниатюру</a>
                                    <label class="white-button inline-button" for="thumbnail_128">Загрузить миниатюру</label>
                                    <p class='thumbnail_128_path'></p>
                                    <input type="file" name="thumbnail_128" id="thumbnail_128" style='display: none' accept="image/jpeg,image/png,image/gif">
                                    <input type='hidden' name='thumbnail_128_select' value=''>
                                    <div class="add-mat-thumbnail-wrap">
                                        @if(pathTo($category->thumbnail_128, 'imagepath'))
                                        <img src="{{ url(pathTo($category->thumbnail_128, 'imagepath')) }}" alt="image" class="add-mat-image">
                                        @else
                                        <img src="{{ asset('assets/images/thumbnails/categories/1.png') }}" alt="image" class="add-mat-image">
                                        @endif
                                        @if($category->thumbnail_128 !== asset('assets/images/thumbnails/categories/1.png'))
                                        <a href="javascript: removeThumbnail128();" class="white-button">Удалить миниатюру</a>
                                        @else
                                        <a style="display: none" href="javascript: removeThumbnail128();" class="white-button">Удалить миниатюру</a>
                                        @endif
                                    </div>
                                </div>
                                <div id="thumbnail_750_tab" style="display: none">
                                    <label class="white-button inline-button" for="thumbnail_750">Загрузить миниатюру</label>
                                    <p class='thumbnail_750_path'></p>
                                    <input type="file" name="thumbnail_750" id="thumbnail_750" style='display: none' accept="image/jpeg,image/png,image/gif">
                                    @if(pathTo($category->thumbnail_750, 'imagepath'))
                                    <div class="add-mat-thumbnail-wrap">
                                        <img src="{{ url(pathTo($category->thumbnail_750, 'imagepath')) }}" alt="image" class="add-mat-image">
                                        <a href="javascript: removeThumbnail750();" class="white-button">Удалить миниатюру</a>
                                    </div>
                                    @else
                                    <a style="display: none;" href="javascript: removeThumbnail750();" class="white-button">Удалить миниатюру</a>
                                    @endif
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
        $("#input[name=image]").val("");
        $(".image_path").text("");
        $("form").append("<input type='hidden' name='image_remove' value='1'>");
    }
    function removeThumbnail128(){
        $("input[name=thumbnail_128_select]").val("{{ asset('assets/images/thumbnails/categories/1.png') }}");
        $("#thumbnail_128_tab img").attr('src', "{{ asset('assets/images/thumbnails/categories/1.png') }}");
        $("input[name=thumbnail_128]").val();
        $("p.thumbnail_128_path").text("");
        $("#thumbnail_128_tab img").show();
        $("a[href='javascript: removeThumbnail128();']").hide();
        $("form").append("<input type='hidden' name='thumbnail_128_remove' value='1'>");
    }
    function removeThumbnail750(){
        $("input[name=thumbnail_750]").val();
        $("p.thumbnail_750_path").text("");
        $("a[href='javascript: removeThumbnail750();']").hide();
        $("#thumbnail_750_tab img").hide();
        $("form").append("<input type='hidden' name='thumbnail_750_remove' value='1'>");
    }
    $(document).ready(function () {
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
        $("select[name=thumbnail_size]").on('selectmenuselect', function(){
            if($(this).val() === "1"){
                $("#thumbnail_128_tab").hide();
                $("#thumbnail_750_tab").show();
            } else {
                $("#thumbnail_750_tab").hide();
                $("#thumbnail_128_tab").show();
            }
        });
        $("input[name=thumbnail_128]").on('change', function () {
            $("p.thumbnail_128_path").text($(this).val());
            $("input[name=thumbnail_128_select]").val("");
            $("#thumbnail_128_tab img").hide();
            $("a[href='javascript: removeThumbnail128();']").show();
        });
        $("#popup_thumbs .thumb_choose a").on("click", function () {
            $("#thumbnail_128_tab img").show();
            $("input[name=thumbnail_128]").val("");
            $("p.thumbnail_128_path").text("");
            $("input[name=thumbnail_128_select]").val($(this).children("img").attr('src'));
            $("#thumbnail_128_tab img").attr('src', $(this).children("img").attr('src'));
            if($(this).children("img").attr('src') !== "{{ asset('assets/images/thumbnails/categories/1.png') }}"){
                $("a[href='javascript: removeThumbnail128();']").show();
            } else {
                $("a[href='javascript: removeThumbnail128();']").hide();
            }
            $.fancybox.close();
        });
        $("input[name=thumbnail_750]").on('change', function () {
            $("p.thumbnail_750_path").text($(this).val());
            $("a[href='javascript: removeThumbnail750();']").show();
        });
        $("input[name=header_dim]").on('click', function(){
            if($(this).prop('checked')){
                $('.add-mat-image-dark').addClass('active');
            } else {
                $('.add-mat-image-dark').removeClass('active');
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
        var datetime = "{{ getDateTime($category->scheduled) }}";
        var local = toLocalDatetime(datetime);
        $("input.datetime").val(local);
        $(".datetime").datetimepicker({
            format: 'd.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate: new Date(),
            defaultDate: local
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;

        $("input[name=offset]").val(offset);

        // ----------------

        @if($category->levels->count())
            @foreach($category->levels as $lvl)
            $('input[name="levels[{{ $lvl->id }}]"]').prop('checked', true);
            @endforeach
            $.uniform.update();
        @endif

        var thumbnail_size = "{{ $category->thumbnail_size }}";
        $("select[name=thumbnail_size]").val(thumbnail_size);
        if(thumbnail_size === "1"){
            $("#thumbnail_128_tab").hide();
            $("#thumbnail_750_tab").show();
        }

        var status = "{{ $category->status }}";
        $("select[name=status]").val(status);
        $(".optional#"+status).show();

        var sch2type = "{{ $category->sch2type }}";
        $("select[name=sch2type]").val(sch2type);

        $("select").selectmenu('refresh');
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
                <img src="{{ asset('assets/images/thumbnails/categories/1.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/2.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/3.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/4.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/5.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/6.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/7.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/8.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/9.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/categories/10.png') }}">
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection