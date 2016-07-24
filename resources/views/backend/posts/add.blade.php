@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/posts">Вернуться к списку публикаций</a>
            </div>
            <div class="content-title">Новая публикация</div>
            <div class="add-material">
                <form action="{{ action('Backend\PostsController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Название публикации</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название публикации" name="name" required="required" maxlength="255" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Содержание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Добавьте содержание вашей публикации. Разместите здесь Ваш продукт, текст, фото, вставьте видео из популярных сервисов, например youtube или vimeo или другую информацию</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <textarea cols="30" rows="10" class="textarea ckedit" placeholder="" name="post_html">{{ Request::old('post_html') }}</textarea>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Вставить код видео<br/><br/><span>Используйте популярные видео-хостинги: <a href='https://www.youtube.com' target="_blank">Youtube</a>, <a href='https://vimeo.com' target="_blank">Vimeo</a>, <a href='https://wistia.com/' target="_blank">Wistia</a></span></div>
                        </div>
                        <div class="add-mat-right">
                            <textarea cols="30" rows="10" class="textarea" placeholder="Вставьте код тут" name="embed">{{ Request::old('embed') }}</textarea>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Добавить видео <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете загрузить видео в формате mp4. Размер видео ограничен оставшимся местом на диске<br/><br/>Использовано места: {{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <ul class="videos">
                                <li class="video">
                                    <span class="hidden">&times;</span>
                                    <label class="white-button inline-button" for="videos1">Загрузить видео</label>
                                    <p class='video_path'></p>
                                    <input type="file" name="videos[]" id="videos1" style='display: none' accept="video/mp4">
                                </li>
                            </ul>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Добавить файлы <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Данные файлы будут доступны для скачивания пользователям. Вы можете загрузить вложения в форматах: pdf, doc, docx, txt, ppt, pptx, xls, xlsx, zip, rar, 7z, jpg, jpeg, png, gif, mp3, mp4, avi. Максимальный размер файла 100мб.<br/><br/>Использовано места: {{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <ul class="files">
                                <li class="file">
                                    <span class="hidden">&times;</span>
                                    <label class="white-button inline-button" for="files1">Загрузить файл</label>
                                    <p class='file_path'></p>
                                    <input type="file" name="files[]" id="files1" style='display: none'>
                                </li>
                            </ul>
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
                            <div class="add-mat-text tooltip-holder">Уровни доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Если не назначить ни один уровень доступа, публикация будет доступна для просмотра только вам</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                @if($project->levels->count())
                                    @foreach($project->levels as $level)
                                    <div class="check-block">
                                        <input data-id='{{ $level->id }}' name="levels[{{ $level->id }}]" type="checkbox" class="check check-level" id="lvl{{ $level->id }}">
                                        <label for="lvl{{ $level->id }}" class="check-label">{{ $level->name }}</label>
                                    </div>
                                    @endforeach
                                @else
                                <p style="color: #c00;">Уровни доступа не созданы!</p>
                                @endif
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Категория <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Категория, в которой будет размещаться ваша публикация. Например, ваша публикация 'Вебинар для начинающих' может размещаться в категории 'Урок 1 - Знакомство'</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="category_id">
                                    @foreach($project->categories()->orderBy('order', 'asc')->get() as $cat)
                                    <option value="{{ $cat->id }}" data-imagedb="{{ $cat->image }}" data-imageurl="{{ url(pathTo($cat->image, 'imagepath')) }}" data-headerdim="{{ (int) $cat->header_dim }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Краткое описание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Краткое описание, которое отображается в списке публикаций</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check">
                                        <label for="excerpt_check" class="check-label">Добавить краткое описание публикации</label>
                                    </div>
                                </div>
                                <div id="excerpt_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание публикации" name="excerpt">{{ Request::old('excerpt') }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Комментарии</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="comments_enabled" type="checkbox" class="check" id="ch3">
                                        <label for="ch3" class="check-label">Разрешить комментарии к публикации</label>
                                    </div>
                                    <div class="check-block" id='comments_moderate' style='display: none'>
                                        <input name="comments_moderate" type="checkbox" class="check" id="ch4">
                                        <label for="ch4" class="check-label tooltip-holder">Модерировать комментарии <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Комментарии будут появляться на странице публикации только после того, как вы их промодерируете и подтвердите</div>">?</span></label>
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
                                <div id="sidebar_type">
                                @else
                                <div id="sidebar_type" style="display: none;">
                                @endif
                                    <label style="display: block; margin-bottom: 10px; cursor: pointer">
                                        <input name="sidebar_type" value="0" type="radio" class="radio" checked="checked">&nbsp;&nbsp;&nbsp;Как на главной проекта
                                    </label>
                                    <div class="clearfix"></div>
                                    <label style="display: block; margin-bottom: 10px; cursor: pointer">
                                        <input name="sidebar_type" value="1" type="radio" class="radio">&nbsp;&nbsp;&nbsp;Как в категории
                                    </label>
                                    <div class="clearfix"></div>
                                    <label style="display: block; cursor: pointer">
                                        <input name="sidebar_type" value="2" type="radio" class="radio">&nbsp;&nbsp;&nbsp;Другой:
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
                                <label class="white-button inline-button" for="image" style="margin-right: 20px;">Загрузить свой фон</label>
                                <p class='image_path'></p>
                                <input type="file" name="image" id="image" style='display: none' accept="image/jpeg,image/png,image/gif">
                                <input type='hidden' name='parent_image' value=''>
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
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Миниатюра <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Миниатюра публикации отображается на странице со списком публикаций</div>">?</span></div>
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
                                    <input type='hidden' name='thumbnail_128_select' value='{{ asset('assets/images/thumbnails/posts/1.png') }}'>
                                    <div class="add-mat-thumbnail-wrap">
                                        <img src="{{ asset('assets/images/thumbnails/posts/1.png') }}" alt="image" class="add-mat-image">
                                        <a style="display: none;" href="javascript: removeThumbnail128();" class="white-button">Удалить миниатюру</a>
                                    </div>
                                </div>
                                <div id="thumbnail_750_tab" style="display: none">
                                    <label class="white-button inline-button" for="thumbnail_750">Загрузить миниатюру</label>
                                    <p class='thumbnail_750_path'></p>
                                    <input type="file" name="thumbnail_750" id="thumbnail_750" style='display: none' accept="image/jpeg,image/png,image/gif">
                                    <a style="display: none;" href="javascript: removeThumbnail750();" class="white-button">Удалить миниатюру</a>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Домашнее задание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете добавить домашнее задание для ваших пользователей к данной публикации</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class='check-list'>
                                    <div class="check-block">
                                        <input name="homework_enabled" type="checkbox" class="check" id="ch5">
                                        <label for="ch5" class="check-label">Добавить домашнее задание</label>
                                    </div>
                                </div>
                                <div id="homework" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст задания" name="homework">{{ Request::old('homework') }}</textarea>
                                    <br/>
                                    <div class="check-block">
                                        <input name="homework_check" type="checkbox" class="check" id="ch6">
                                        <label for="ch6" class="check-label tooltip-holder">Модерировать домашнее задание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Задание будет считаться выполненным только после вашей модерации. Если данная опция не выбрана, наличие любого домашнего задание будет считаться выполнением</div>">?</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class='add-mat-left'>
                                <div class='add-mat-text tooltip-holder'>Ограничение доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Пользователю не будет доступна данная публикация, пока он не выполнит домашние задания в публикациях отмеченных галочками</div>">?</span></div>
                            </div>
                            <div class='add-mat-right'>
                                @if($project->posts()->where('homework_enabled', true)->count())
                                <label>Если не выполнено домашнее задание в следующих публикациях:</label>
                                <div class='check-list'>
                                    @foreach($project->posts()->where('homework_enabled', true)->get() as $post)
                                    <div class="check-block">
                                        <input name="homeworks[{{ $post->id }}]" type="checkbox" class="check" id="req{{ $post->id }}">
                                        <label for="req{{ $post->id }}" class="check-label">{{ $post->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p>Добавьте домашнее задание минимум еще к одной другой публикации, чтобы ограничить доступ к этой публикации</p>
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
    var cats = {};
    @foreach($project->categories as $cat2)
        cats[{{ $cat2->id }}] = [];
        @foreach($cat2->levels as $lvl2)
        cats[{{ $cat2->id }}].push({{ $lvl2->id }});
        @endforeach
    @endforeach
    function levelsUpdate(cats){
        var active_cat = $("select[name=category_id]").val();
        $("input.check-level").each(function(){
            var level_id = $(this).data("id");
            if(cats[active_cat].indexOf(level_id) !== -1){
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
        $.uniform.update();
    }
    var parent_trigger = true;
    function parentImageUpdate(){
        var active_cat = $("select[name=category_id]").val();
        var image_db = $("select[name=category_id]").find(':selected').data('imagedb');
        var image_url = $("select[name=category_id]").find(':selected').data('imageurl');
        var header_dim = $("select[name=category_id]").find(':selected').data('headerdim');
        if(image_db.length > 0 && image_url.length > 0){
            $("input[name=parent_image]").val(image_db);
            $("#selected_image img").attr('src', image_url);
            $("#selected_image").show();
        } else {
            $("input[name=parent_image]").val("");
            $("#selected_image img").attr('src', "");
            $("#selected_image").hide();
        }
        if(parseInt(header_dim)){
            $('input[name=header_dim]').prop('checked', true);
            $("#selected_image .add-mat-image-dark").addClass('active');
        } else {
            $('input[name=header_dim]').prop('checked', false);
            $("#selected_image .add-mat-image-dark").removeClass('active');
        }
        $.uniform.update();
    }
    function removeImage(){
        $("#selected_image").hide();
        $("input[name=parent_image]").val('');
        $("input[name=image_select]").val('');
        $("input[name=image]").val();
        $("p.image_path").text("");
        parent_trigger = false;
    }
    function removeThumbnail128(){
        $("input[name=thumbnail_128_select]").val("{{ asset('assets/images/thumbnails/posts/1.png') }}");
        $("#thumbnail_128_tab img").attr('src', "{{ asset('assets/images/thumbnails/posts/1.png') }}");
        $("input[name=thumbnail_128]").val();
        $("p.thumbnail_128_path").text("");
        $("#thumbnail_128_tab img").show();
        $("a[href='javascript: removeThumbnail128();']").hide();
    }
    function removeThumbnail750(){
        $("input[name=thumbnail_750]").val();
        $("p.thumbnail_750_path").text("");
        $("a[href='javascript: removeThumbnail750();']").hide();
    }
    $(document).ready(function () {
        $("input[name=image]").on('change', function () {
            $("p.image_path").text($(this).val());
            $("input[name=parent_image]").val('');
            $("input[name=image_select]").val("");
            $("#selected_image").hide();
            parent_trigger = false;
        });
        $("#popup_images .image_choose a").on("click", function () {
            $("input[name=parent_image]").val('');
            $("input[name=image]").val("");
            $("p.image_path").text("");
            $("input[name=image_select]").val($(this).children("img").attr('src'));
            $("#selected_image img").attr('src', $(this).children("img").attr('src'));
            $("#selected_image").slideDown();
            parent_trigger = false;
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
            if($(this).children("img").attr('src') !== "{{ asset('assets/images/thumbnails/posts/1.png') }}"){
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
        $("input[name=header_dim]").on('change', function(){
            parent_trigger = false;
            if($(this).prop('checked')){
                $("#selected_image .add-mat-image-dark").addClass('active');
            } else {
                $("#selected_image .add-mat-image-dark").removeClass('active');
            }
        });
        $("input[name=video]").on('change', function () {
            $("p.video_path").text($(this).val());
        });

        var count = 1;

        $(".files").on('change', "input[name='files[]']", function () {
            $(this).siblings("span").removeClass('hidden');
            var current = $(this).siblings("p.file_path").text();
            $(this).siblings("p.file_path").text($(this).val());
            if(!current.length){
                count++;
                $(".files").append("<li class='file'><span class='hidden'>&times;</span> <label class='white-button inline-button' for='files"+count+"'>Загрузить файл</label><p class='file_path'></p><input type='file' name='files[]' id='files"+count+"' style='display: none'></li>");
            }
            $('.files .file span').on('click', function(){
                $(this).parent("li.file").remove();
            });
        });

        count = 1;

        $(".videos").on('change', "input[name='videos[]']", function () {
            $(this).siblings("span").removeClass('hidden');
            var current = $(this).siblings("p.video_path").text();
            $(this).siblings("p.video_path").text($(this).val());
            if(!current.length){
                count++;
                $(".videos").append("<li class='video'><span class='hidden'>&times;</span> <label class='white-button inline-button' for='videos"+count+"'>Загрузить видео</label><p class='video_path'></p><input type='file' name='videos[]' id='videos"+count+"' style='display: none'></li>");
            }
            $('.videos .video span').on('click', function(){
                $(this).parent("li.video").remove();
            });
        });

        $("input[name=homework_enabled]").on('change', function(){
            if($(this).prop('checked')){
                $("#homework").slideDown(200);
            } else {
                $("#homework").slideUp(200);
            }
        });

        $("input[name=excerpt_check]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#excerpt_field").slideDown(200);
            } else {
                $("#excerpt_field textarea").val("");
                $("#excerpt_field").slideUp(200);
            }
        });
        
        

        $("input[name=homework_required]").on('change', function(){
            if($(this).prop('checked')){
                $("#homework_required").slideDown(200);
            } else {
                $("#homework_required").find('input.check').prop('checked', false);
                $.uniform.update();
                $("#homework_required").slideUp(200);
            }
        });
        $("input[name=sidebar]").on('change', function(){
            if($(this).prop('checked')){
                $("#sidebar_type").slideDown();
                if(parseInt($("input[name=sidebar_type]:checked").val()) === 2){
                    $("#sidebar_html_field").slideDown();
                }
            } else {
                $("#sidebar_type").slideUp();
                $("#sidebar_html_field").slideUp();
            }
        });
        $("input[name=sidebar_type]").on('change', function(){
            if(parseInt($(this).val()) === 2){
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
        $("input[name=comments_enabled]").on('click', function(){
            if($(this).prop('checked')){
                $("#comments_moderate").slideDown();
            } else {
                $("#comments_moderate").slideUp();
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

        $("select[name=category_id]").on("selectmenuselect", function(){
            levelsUpdate(cats);
            if(parent_trigger){
                parentImageUpdate();
            }
        });
        
        var loading = $('#loading_screen');
        var percent = $('#loading_screen span');
        percent.css({'font-weight':'700', 'font-size':'22px'});
        
        $('.add-material form').ajaxForm({
            dataType: 'json',
            beforeSend: function() {
                loading.fadeIn(200);
                var percentVal = '0%';
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                percent.html(percentVal);
            },
            success: function(data){
                if(data.error){
                    $("#popup_ajax_errors .popup-min-title").text("Ошибка:");
                    $("#popup_ajax_errors ul").html("");
                    $("#popup_ajax_errors ul").append("<li>- "+data.error+"</li>");
                    loading.fadeOut(200);
                    $('body').css('overflow', 'auto');
                    $.fancybox("#popup_ajax_errors");
                } else if(data.success) {
                    window.location.href = "/posts";
                } else {
                    $("#popup_ajax_errors .popup-min-title").text("Ошибка сервера:");
                    $("#popup_ajax_errors ul").html("");
                    $("#popup_ajax_errors ul").append("<li>Неизвестная ошибка сервера! Обратитесь в техподдержку</li>");
                    loading.fadeOut(200);
                    $('body').css('overflow', 'auto');
                    $.fancybox("#popup_ajax_errors");
                }
            },
            error: function(data){
                var errorsJSON = jQuery.parseJSON(data.responseText);
                var errors = $.map(errorsJSON, function(el){
                    return el;
                });
                if(errors.length === 1){
                    $("#popup_ajax_errors .popup-min-title").text("Ошибка:");
                } else {
                    $("#popup_ajax_errors .popup-min-title").text("Ошибки:");
                }
                $("#popup_ajax_errors ul").html("");
                errors.forEach(function(element){
                    $("#popup_ajax_errors ul").append("<li>- "+element+"</li>");
                });
                loading.fadeOut(200);
                $('body').css('overflow', 'auto');
                $.fancybox("#popup_ajax_errors");
            }
        });
    });
    $(window).load(function(){
        levelsUpdate(cats);
        parentImageUpdate();
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
                <img src="{{ asset('assets/images/thumbnails/posts/1.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/2.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/3.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/4.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/5.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/6.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/7.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/8.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/9.png') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/10.png') }}">
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<style>
    li.file span, li.video span {
        color: #c00;
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
        margin: 0px 10px 0px 0px;
        font-size: 18px;
    }
    li.file span.hidden, li.video span.hidden {
        opacity: 0;
        height: 0px;
        overflow: hidden;
    }
    li.file label, li.video label {
        margin: 0px;
    }
    li.file, li.video {
        margin: 0px 0px 15px 0px;
    }
</style>
@endsection