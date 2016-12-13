@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/posts">Вернуться к списку публикаций</a>
            </div>
            <div class="content-title">Редактировать публикацию "{{ $post->name }}"<a href="{{ getPreviewLink('post', $post->id) }}" target="_blank"></a></div>
            <div class="add-material">
                <form action="{{ action('Backend\PostsController@update', ['post_id' => $post->id]) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Название публикации</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите название публикации" name="name" required="required" maxlength="255" value="{{ $post->name }}">
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
                                <input type='hidden' name='thumbnail_select' value=''>
                                <div class="add-mat-thumbnail-wrap">
                                    @if(pathTo($post->thumbnail, 'imagepath'))
                                    <img src="{{ url(pathTo($post->thumbnail, 'imagepath')) }}" alt="image" class="add-mat-image">
                                    @else
                                    <img src="{{ asset('assets/images/thumbnails/posts/1.jpg') }}" alt="image" class="add-mat-image">
                                    @endif
                                    <div class="check-list" id='thumbnail_save_prop' style='display: none;'>
                                        <div class="check-block">
                                            <input name="thumbnail_save_prop" type="checkbox" class="check" id="thumbnail_save_prop_check">
                                            <label for="thumbnail_save_prop_check" class="check-label">Сохранять пропорции (не обрезать 16:10)</label>
                                        </div>
                                    </div>
                                    @if($post->thumbnail !== asset('assets/images/thumbnails/posts/1.jpg'))
                                    <a href="javascript: removeThumbnail();" class="white-button">Удалить миниатюру</a>
                                    @else
                                    <a style="display: none" href="javascript: removeThumbnail();" class="white-button">Удалить миниатюру</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Содержание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Добавьте содержание вашей публикации. Разместите здесь Ваш продукт, текст, фото, вставьте видео из популярных сервисов, например youtube или vimeo или другую информацию</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <textarea cols="30" rows="10" class="textarea ckedit" placeholder="" name="post_html">{{ $post->post_html }}</textarea>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Вставить код видео<br/><br/><span>Используйте популярные видео-хостинги: <a href='https://www.youtube.com' target="_blank">Youtube</a>, <a href='https://vimeo.com' target="_blank">Vimeo</a>, <a href='https://wistia.com/' target="_blank">Wistia</a></span></div>
                        </div>
                        <div class="add-mat-right">
                            <textarea cols="30" rows="10" class="textarea" placeholder="Вставьте код тут" name="embed">{{ $post->embed }}</textarea>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Добавить видео <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете загрузить видео в формате mp4. Размер видео ограничен оставшимся местом на диске<br/><br/>Использовано места: {{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            @if($post->videos->count())
                            <ul class="current_videos">
                                @foreach($post->videos as $video)
                                <li data-id="{{ $video->id }}"><span>&times;</span>{{ $video->name }}</li>
                                @endforeach
                            </ul>
                            @endif
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
                            @if($post->files->count())
                            <ul class="current_files">
                                @foreach($post->files as $file)
                                <li data-id="{{ $file->id }}"><span>&times;</span>{{ $file->name }}</li>
                                @endforeach
                            </ul>
                            @endif
                            <ul class="files">
                                <li class="file">
                                    <span class="hidden">&times;</span>
                                    <label class="white-button inline-button" for="files1">Загрузить файл</label>
                                    <p class='file_path'></p>
                                    <input type="file" name="files[]" id="files1" style='display: none'>
                                </li>
                            </ul>
                        </div>
                        @if($project->ipr_levels()->count())
                        <div class="add-mat-title">InfoProtector™</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Уровень</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="ipr_level_id">
                                    <option value="0">Не назначать</option>
                                    @foreach($project->ipr_levels as $ipr_level)
                                        @if($post->ipr_level_id === $ipr_level->id)
                                        <option value="{{ $ipr_level->id }}" selected="selected">{{ $ipr_level->name }}</option>
                                        @else
                                        <option value="{{ $ipr_level->id }}">{{ $ipr_level->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
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
                                <input type="text" class="input datetime" placeholder="Дата" name="scheduled" value="{{ $post->scheduled }}">
                                <input type="hidden" name="offset" value="">
                                <div class="check-block">
                                    @if($post->comingsoon)
                                    <input name="comingsoon1" type="checkbox" class="check" id="comingsoon1" checked="checked">
                                    @else
                                    <input name="comingsoon1" type="checkbox" class="check" id="comingsoon1">
                                    @endif
                                    <label for="comingsoon1" class="check-label">Отображать запланированую дату публикации</label>
                                </div>
                                <div class="material-note">Предпросмотр публикации будет доступен только администратору</div>
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
                                    @if($post->comingsoon)
                                    <input name="comingsoon2" type="checkbox" class="check" id="comingsoon2" checked="checked">
                                    @else
                                    <input name="comingsoon2" type="checkbox" class="check" id="comingsoon2">
                                    @endif
                                    <label for="comingsoon2" class="check-label">Отображать запланированую дату публикации</label>
                                </div>
                                <div class="material-note">Предпросмотр публикации будет доступен только администратору</div>
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
                                    @if($post->category->id === $cat->id)
                                    <option value="{{ $cat->id }}" selected="selected">{{ $cat->name }}</option>
                                    @else
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-optional-title">Дополнительные настройки</div>
                        <div id='optional'>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Атрибут <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Строчка, которая будет показыватся поверх миниатюры</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="select-block">
                                    <select class="styled" name="stripe">
                                        <option value="0">Не использовать</option>
                                        <option value="soon">"Скоро будет"</option>
                                        <option value="updated">"Обновлено"</option>
                                        <option value="new">"Новинка"</option>
                                    </select>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Краткое описание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Краткое описание, которое отображается в списке публикаций</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if(!empty($post->excerpt))
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check" checked="checked">
                                        @else
                                        <input name="excerpt_check" type="checkbox" class="check" id="excerpt_check">
                                        @endif
                                        <label for="excerpt_check" class="check-label">Добавить краткое описание публикации</label>
                                    </div>
                                </div>
                                @if(!empty($post->excerpt))
                                <div id="excerpt_field">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание" name="excerpt">{{ $post->excerpt }}</textarea>
                                </div>
                                @else
                                <div id="excerpt_field" style="display: none">
                                    <textarea cols="30" rows="10" class="textarea" placeholder="Введите краткое описание" name="excerpt">{{ $post->excerpt }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Комментарии</div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($post->comments_enabled)
                                        <input name="comments_enabled" type="checkbox" class="check" id="ch3" checked="checked">
                                        @else
                                        <input name="comments_enabled" type="checkbox" class="check" id="ch3">
                                        @endif
                                        <label for="ch3" class="check-label">Разрешить комментрировать публикацию</label>
                                    </div>
                                    @if($post->comments_enabled)
                                    <div class="check-block" id='comments_moderate'>
                                    @else
                                    <div class="check-block" id='comments_moderate' style='display: none'>
                                    @endif
                                        @if($post->comments_moderate)
                                        <input name="comments_moderate" type="checkbox" class="check" id="ch4" checked="checked">
                                        @else
                                        <input name="comments_moderate" type="checkbox" class="check" id="ch4">
                                        @endif
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
                                        @if($post->sidebar)
                                        <input name="sidebar" type="checkbox" class="check" id="chside" checked="checked">
                                        @else
                                        <input name="sidebar" type="checkbox" class="check" id="chside">
                                        @endif
                                        <label for="chside" class="check-label tooltip-holder">Отображать сайдбар <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В сайдбаре (боковой панели) вы можете добавить контент, который будет отображаться на каждой странице проекта справа. Например, контакты или любую другую информацию</div>">?</span></label>
                                    </div>
                                </div>
                                @if($post->sidebar)
                                <div id="sidebar_type">
                                @else
                                <div id="sidebar_type" style="display: none;">
                                @endif
                                    <label style="display: block; margin-bottom: 10px; cursor: pointer">
                                        <input name="sidebar_type" value="0" type="radio" class="radio">&nbsp;&nbsp;&nbsp;Как на главной проекта
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
                                @if($post->sidebar_type === 2 && $post->sidebar)
                                <div id="sidebar_html_field" style="margin-top: 20px;">
                                @else
                                <div id="sidebar_html_field" style="display: none; margin-top: 20px;">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите контент для сайдбара" name="sidebar_html">{{ $post->sidebar_html }}</textarea>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Добавить апсейл <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Например, если у пользователя неподходящий уровень доступа, ему можно показать предложение повысить тарифный план или другое сообщение</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($post->upsale)
                                        <input name="upsale" type="checkbox" class="check" id="ch1" checked="checked">
                                        @else
                                        <input name="upsale" type="checkbox" class="check" id="ch1">
                                        @endif
                                        <label for="ch1" class="check-label">Показывать текст для пользователей с неподходящим уровнем доступа:</label>
                                    </div>
                                </div>
                                @if($post->upsale)
                                <div id="upsale">
                                @else
                                <div style="display: none;" id="upsale">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст" name="upsale_text">{{ $post->upsale_text }}</textarea>
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
                                <input type='hidden' name='image_select' value=''>
                                <div class="check-list">
                                    <div class="check-block">
                                        @if($post->header_dim)
                                        <?php $header_dim = "checked = 'checked'"; $dark_class = "active"; ?>
                                        @else
                                        <?php $header_dim = ""; $dark_class = ""; ?>
                                        @endif
                                        <input name="header_dim" type="checkbox" class="check" id="chdim" {{ $header_dim }}>
                                        <label for="chdim" class="check-label tooltip-holder">Затемнить фон <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>В зависимости от фона, его затемнение может сделать заголовок и подзаголовок на нем более контрастными</div>">?</span></label>
                                    </div>
                                </div>
                                @if(pathTo($post->image, 'imagepath'))
                                <div id="selected_image">
                                    <div class="add-mat-image-wrap">
                                        <img src="{{ url(pathTo($post->image, 'imagepath')) }}" alt="image" class="add-mat-image">
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
                                <div class="add-mat-text tooltip-holder">Домашнее задание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете добавить домашнее задание для ваших пользователей к данной публикации</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class='check-list'>
                                    <div class="check-block">
                                        @if($post->homework_enabled)
                                        <input name="homework_enabled" type="checkbox" class="check" id="ch5" checked="checked">
                                        @else
                                        <input name="homework_enabled" type="checkbox" class="check" id="ch5">
                                        @endif
                                        <label for="ch5" class="check-label">Добавить домашнее задание</label>
                                    </div>
                                </div>
                                @if($post->homework_enabled)
                                <div id="homework">
                                @else
                                <div id="homework" style="display: none">
                                @endif
                                    <textarea cols="30" rows="10" class="textarea ckedit" placeholder="Введите текст задания" name="homework">{{ $post->homework }}</textarea>
                                    <br/>
                                    <div class="check-block">
                                        @if($post->homework_check)
                                        <input name="homework_check" type="checkbox" class="check" id="ch6" checked="checked">
                                        @else
                                        <input name="homework_check" type="checkbox" class="check" id="ch6">
                                        @endif
                                        <label for="ch6" class="check-label tooltip-holder">Модерировать домашнее задание <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Задание будет считаться выполненным только после вашей модерации. Если данная опция не выбрана, наличие любого домашнего задание будет считаться выполнением</div>">?</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class='add-mat-text tooltip-holder'>Ограничение доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Пользователю не будет доступна данная публикация, пока он не выполнит домашние задания в публикациях отмеченных галочками</div>">?</span></div>
                            </div>
                            <div class='add-mat-right'>
                                <?php $required_now = Array(); ?>
                                @if($post->requiredPosts->count())
                                <?php foreach($post->requiredPosts as $req){
                                    array_push($required_now, $req->id);
                                } ?>
                                @endif
                                @if($project->posts()->where('homework_enabled', true)->where('posts.id', '!=', $post->id)->count())
                                <label>Если не выполнено домашнее задание в следующих публикациях:</label>
                                <div class='check-list'>
                                    @foreach($project->posts()->where('homework_enabled', true)->where('posts.id', '!=', $post->id)->get() as $p)
                                    <div class="check-block">
                                        @if(in_array($p->id, $required_now))
                                        <input name="homeworks[{{ $p->id }}]" type="checkbox" class="check" id="req{{ $p->id }}" checked="checked">
                                        @else
                                        <input name="homeworks[{{ $p->id }}]" type="checkbox" class="check" id="req{{ $p->id }}">
                                        @endif
                                        <label for="req{{ $p->id }}" class="check-label">{{ $p->name }}</label>
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
    function removeImage(){
        $("#selected_image").hide();
        $("#input[name=image]").val("");
        $(".image_path").text("");
        $("form").append("<input type='hidden' name='image_remove' value='1'>");
    }
    function removeThumbnail(){
        $("input[name=thumbnail_select]").val("{{ asset('assets/images/thumbnails/posts/1.jpg') }}");
        $("#thumbnail_tab img").attr('src', "{{ asset('assets/images/thumbnails/posts/1.jpg') }}");
        $("input[name=thumbnail]").val("");
        $("p.thumbnail_path").text("");
        $("#thumbnail_tab img").show();
        $("#thumbnail_save_prop").hide();
        $("a[href='javascript: removeThumbnail();']").hide();
        $("form").append("<input type='hidden' name='thumbnail_remove' value='1'>");
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
        $("input[name=header_dim]").on('click', function(){
            if($(this).prop('checked')){
                $('.add-mat-image-dark').addClass('active');
            } else {
                $('.add-mat-image-dark').removeClass('active');
            }
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
            if($(this).children("img").attr('src') !== "{{ asset('assets/images/thumbnails/posts/1.jpg') }}"){
                $("a[href='javascript: removeThumbnail();']").show();
            } else {
                $("a[href='javascript: removeThumbnail();']").hide();
            }
            $.fancybox.close();
        });

        $('.current_files li span').on('click', function(){
            var file_id = $(this).parent('li').data('id');
            $(".add-material form").append("<input type='hidden' name='files_delete[]' value='"+file_id+"'>");
            $(this).parent("li").remove();
            if($(".current_files li").length <= 0){
                $(".current_files").remove();
            }
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

        $('.current_videos li span').on('click', function(){
            var video_id = $(this).parent('li').data('id');
            $(".add-material form").append("<input type='hidden' name='videos_delete[]' value='"+video_id+"'>");
            $(this).parent("li").remove();
            if($(".current_videos li").length <= 0){
                $(".current_videos").remove();
            }
        });

        var count = 1;

        $(".videos").on('change', "input[name='videos[]']", function () {
            $(this).siblings("span").removeClass('hidden');
            var current = $(this).siblings("p.video_path").text();
            $(this).siblings("p.video_path").text($(this).val());
            if(!current.length){
                count++;
                $(".videos").append("<li class='video'><span class='hidden'>&times;</span> <label class='white-button inline-button' for='videos"+count+"'>Загрузить видео</label><p class='video_path'></p><input type='file' name='videos[]' id='videos"+count+"' style='display: none' accept='video/mp4'></li>");
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

        $("input[name=embed_check]").on('change', function () {
            if ($(this).prop('checked')) {
                $("#embed_field").slideDown(200);
            } else {
                $("#embed_field .textarea").val("");
                $("#embed_field").slideUp(200);
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

        $("input[name=homework_required]").on('change', function(){
            if($(this).prop('checked')){
                $("#homework_required").slideDown(200);
            } else {
                $("#homework_required").find('input.check').prop('checked', false);
                $.uniform.update();
                $("#homework_required").slideUp(200);
            }
        });
        
        $("input[name=sidebar_type][value={{ $post->sidebar_type }}]").prop('checked', true);
        $.uniform.update();
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
        var datetime = "{{ getDateTime($post->scheduled) }}";
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

        @if($post->levels->count())
            @foreach($post->levels as $lvl2)
            $('input[name="levels[{{ $lvl2->id }}]"]').prop('checked', true);
            @endforeach
            $.uniform.update();
        @endif

        var status = "{{ $post->status }}";
        $("select[name=status]").val(status);
        $(".optional#"+status).show();

        var sch2type = "{{ $post->sch2type }}";
        $("select[name=sch2type]").val(sch2type);

        $("select").selectmenu('refresh');

        $("input[name=comingsoon1]").on("change", function(){
            if($(this).prop('checked')){
                $("input[name=comingsoon2]").prop('checked', true);
            } else {
                $("input[name=comingsoon2]").prop('checked', false);
            }
            $.uniform.update();
        });
        $("input[name=comingsoon2]").on("change", function(){
            if($(this).prop('checked')){
                $("input[name=comingsoon1]").prop('checked', true);
            } else {
                $("input[name=comingsoon1]").prop('checked', false);
            }
            $.uniform.update();
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
                    window.location.reload();
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
        
        @if($post->stripe)
        $("select[name=stripe]").val("{{ $post->stripe }}");
        $("select").selectmenu('refresh');
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
                <img src="{{ asset('assets/images/thumbnails/posts/1.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/2.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/3.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/4.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/5.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/6.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/7.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/8.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/9.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/10.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/11.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/12.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/13.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/14.jpg') }}">
            </a>
            <a href="javascript: void(0);">
                <img src="{{ asset('assets/images/thumbnails/posts/15.jpg') }}">
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<style>
    .current_files, .current_videos {
        display: block;
        border-bottom: 1px solid #d9d9d9;
        margin-bottom: 15px;
    }
    .current_files li, .current_videos li {
        display: block;
        margin: 0px 0px 10px 0px;
    }
    .current_files li span, .current_videos li span {
        color: #c00;
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
        margin: 0px 10px 0px 0px;
        font-size: 18px;
    }
    li.file span, .current_video span, li.video span {
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