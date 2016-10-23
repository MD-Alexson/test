@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($category->posts->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Публикации ({{ $category->name }})</div>
                    <div class="project-top-num">{{ $category->posts->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/posts/add/category/{{ $category->id }}" class="green-button float-right">Добавить публикацию</a>
                    <a href="/posts/by_category/{{ $category->id }}/order" class="white-button float-right">Порядок отображения</a>
                    <a href="{{ getPreviewLink('category', $category->id) }}" class="white-button float-right" target="_blank">Предпросмотр</a>
                </div>
            </div>
            <div class="content-back">
                <a href="/posts">К списку всех публикаций</a>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\PostsController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="category">Изменить категорию</option>
                                <option value="levels">Назначить уровни доступа</option>
                                <option value="status">Изменить статус</option>
                                <option value="comments">Комментарии</option>
                                <option value="delete">Удалить</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_comments">
                        <div class="material-control-text">Комментарии</div>
                        <div class="check-list">
                            <div class="check-block">
                                <input name="comments_enabled" type="checkbox" class="check" id="b3">
                                <label for="b3" class="check-label">Разрешить комментарии</label>
                            </div>
                            <div class="check-block">
                                <input name="comments_moderate" type="checkbox" class="check" id="b4">
                                <label for="b4" class="check-label">Модерировать комментарии</label>
                            </div>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_category">
                        <div class="material-control-text">Категория</div>
                        <div class="select-block">
                            <select class="styled" name="category_id">
                                @foreach($project->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_levels">
                        @if($project->levels->count() > 1)
                        <div class="whitefade"></div>
                        <style>
                            #batch_levels:hover .check-list {
                                background: #fff;
                                border-bottom: 2px solid #ccc;
                            }
                        </style>
                        @endif
                        <div class="material-control-text">Уровни доступа</div>
                        <div class="check-list">
                            @foreach($project->levels as $lvl)
                            <div class="check-block">
                                <input name="levels[{{ $lvl->id }}]" type="checkbox" class="check" id="ch{{ $lvl->id }}">
                                <label for="ch{{ $lvl->id }}" class="check-label">{{ $lvl->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_status">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="status">
                                <option value="published">Опубликовано</option>
                                <option value="draft">Черновик</option>
                                <option value="scheduled">Запланировано на дату</option>
                                <option value="scheduled2">Запланировано от да регистрации пользователя</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch2" id="batch2_status_scheduled">
                        <div class="material-control-text">Дата публикации:</div>
                        <input type='text' class='input datetime' name='scheduled' value="">
                        <input type="hidden" name="offset" value="">
                        <div class="check-block">
                            <input name="comingsoon1" type="checkbox" class="check" id="ch_comingsoon1">
                            <label for="ch_comingsoon1" class="check-label">Отображать дату</label>
                        </div>
                    </div>
                    <div class="material-control-item batch2" id="batch2_status_scheduled2">
                        <div class="material-control-text">Опубликовать:</div>
                        <input type="number" class="input" value="1" name="sch2num" placeholder="1" min="1">
                        <div class="select-block">
                            <select class="styled" name="sch2type">
                                <option value="days">Дней</option>
                                <option value="weeks">Недель</option>
                                <option value="months">Месяцев</option>
                            </select>
                        </div>
                        <div class="check-block">
                            <input name="comingsoon2" type="checkbox" class="check" id="ch_comingsoon2">
                            <label for="ch_comingsoon2" class="check-label">Отображать дату</label>
                        </div>
                    </div>
                    <div class="material-control-item">
                        <input type="hidden" name="ids" value="">
                        <button type="submit" class="blue-button">Применить</button>
                    </div>
                    <div class="material-controls-mask"></div>
                </form>
                <?php
                if(Session::get('sort.posts.order') === 'desc'){
                    $order = 'asc';
                } else {
                    $order = 'desc';
                }
                ?>
                <div class="material-table">
                    <table>
                        <col width="4%">
                        <col width="4%">
                        <col width="20%">
                        <col width="14%">
                        <col width="14%">
                        <col width="14%">
                        <col width="20%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td><a data-sort="order" href='/sort/posts/order/{{ $order }}' class='table-sort'>#</a></td>
                            <td><a data-sort="name" href='/sort/posts/name/{{ $order }}' class='table-sort'>Название</a></td>
                            <td><a data-sort="category_id" href='/sort/posts/category_id/{{ $order }}' class='table-sort'>Категория</a></td>
                            <td>Уровни доступа</td>
                            <td><a data-sort="status" href='/sort/posts/status/{{ $order }}' class='table-sort'>Статус</a></td>
                            <td>Активность</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($posts as $post)
                        <tr class="row" data-id="{{ $post->id }}" id="{{ $post->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td><a href="/posts/by_category/{{ $category->id }}/order" title="Изменить порядок в категории">{{ $post->order }}</a></td>
                            <td>
                                <a class="table-title" href="/posts/{{ $post->id }}/edit">{{ $post->name }}</a>
                            </td>
                            <td>
                                <a href="/posts/by_category/{{ $post->category->id }}">{{ $post->category->name }}</a>
                            </td>
                            <td>
                                @if($post->levels->count())
                                <div class="table_levels">
                                    @if($post->levels->count() > 1)
                                    <div class="whitefade"></div>
                                    <style>
                                        #row<?php echo $post->id; ?> .table_levels:hover > div:not(.whitefade){
                                            border-bottom: 2px solid #ccc;
                                        }
                                    </style>
                                    @endif
                                    <div>
                                        @foreach($post->levels as $level)
                                        <a href='/posts/by_level/{{ $level->id }}'>- {{ $level->name }}</a><br/>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <strong>—</strong>
                                @endif
                            </td>
                            <td>
                                @if($post->status === 'published')
                                <span style='color: #72c01d;'>Опубликовано</span>
                                @elseif($post->status === 'draft')
                                Черновик
                                @elseif($post->status === 'scheduled')
                                Запланировано на:<br/><span class='toLocalTime' style='font-weight: 700'>{{ getDateTime($post->scheduled) }}</span>
                                    @if($post->comingsoon)
                                    <br/>
                                    - Отображать дату
                                    @endif
                                @elseif($post->status === 'scheduled2')
                                Запланировано (от даты регистрации пользователя):<br/>
                                через <strong>{{ $post->sch2num }} {{ $post->sch2typename }}</strong>
                                    @if($post->comingsoon)
                                    <br/>
                                    - Отображать дату
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($post->comments_enabled)
                                <a href="/comments/by_post/{{ $post->id }}">Комментарии ({{ $post->comments->count() }})</a>
                                @else
                                Комментарии (—)
                                @endif
                                <br/>
                                @if($post->homework_enabled)
                                <a href="/homeworks/by_post/{{ $post->id }}">ДЗ ({{ $post->homeworks->count() }})</a>
                                @else
                                ДЗ (—)
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="{{ getPreviewLink('post', $post->id) }}" class="cab-icon cab-icon1" target="_blank"></a>
                                    <a href="/posts/{{ $post->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_post_delete" class="cab-icon cab-icon3 fancybox post-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @include('backend.inc.pagination', ['entities' => $posts])
            </div>
            @elseif(!$project->posts->count() && !$project->categories->count())
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет публикаций и категорий</div>
                <div class="add-project-text">Для создания публикации необходима хотя бы одна категория</div>
                <div class="add-project-button">
                    <a href="/categories/add" class="green-button">Создать категорию</a>
                </div>
            </div>
            @elseif($project->posts->count())
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет публикаций<br/>в данной категории</div>
                <div class="add-project-text">Создайте новую публикацию</div>
                <div class="add-project-button">
                    <a href="/posts/add/category/{{ $category->id }}" class="green-button">Создать публикацию</a>
                </div>
                <a href="javascript: history.back()">Назад</a>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет публикаций</div>
                <div class="add-project-text">Создайте первую публикацию</div>
                <div class="add-project-button">
                    <a href="/posts/add/category/{{ $category->id }}" class="green-button">Создать публикацию</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_post_delete" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить публикацию "<span></span>"?</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">При этом удалятся все файлы, загруженные видео, комментарии и домашние задания!</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.material-table .cab-icon.post-delete').on('click', function () {
            var id = $(this).closest("tr").data('id');
            var name = $(this).closest("tr").find(".table-title").text();

            $("#popup_post_delete a.red-button").attr('href', "/posts/" + id + "/delete");
            $("#popup_post_delete .popup-min-title span").text(name);
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
        
        var sort = "{{ Session::get('sort.posts.order_by') }}";
        $("a.table-sort[data-sort='"+sort+"']").css({'font-weight':'700', 'text-decoration':'underline'});
    });
</script>
@endsection