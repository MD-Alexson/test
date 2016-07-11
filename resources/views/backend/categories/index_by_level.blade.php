@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($categories->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Категории ({{ $level->name }})</div>
                    <div class="project-top-num">{{ $categories->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/categories/add" class="green-button">Добавить категорию</a>
                </div>
            </div>
            <div class="content-back">
                <a href="/categories">К списку всех категорий</a>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\CategoriesController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="levels">Назначить уровни доступа</option>
                                <option value="status">Изменить статус</option>
                                <option value="delete">Удалить</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_levels">
                        <div class="material-control-text">Уровни доступа</div>
                        @if($project->levels->count() > 1)
                        <div class="whitefade"></div>
                        <style>
                            #batch_levels:hover .check-list {
                                background: #fff;
                                border-bottom: 2px solid #ccc;
                            }
                        </style>
                        @endif
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
                if(Session::get('sort.categories.order') === 'desc'){
                    $order = 'asc';
                } else {
                    $order = 'desc';
                }
                ?>
                <div class="material-table">
                    <table>
                        <col width="4%">
                        <col width="32%">
                        <col width="14%">
                        <col width="20%">
                        <col width="20%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td><a data-sort="name" href='/sort/categories/name/{{ $order }}' class='table-sort'>Название</a></td>
                            <td style="text-align: center">Публикации</td>
                            <td>Уровни доступа</td>
                            <td><a data-sort="status" href='/sort/categories/status/{{ $order }}' class='table-sort'>Статус</a></td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($categories as $category)
                        <tr class="row" data-id="{{ $category->id }}" id="row{{ $category->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td>
                                <a class="table-title" href="/categories/{{ $category->id }}/edit">{{ $category->name }}</a>
                            </td>
                            <td style="text-align: center">
                                <a href="/posts/by_category/{{ $category->id }}">{{ $category->posts->count() }}</a>
                            </td>
                            <td>
                                @if($category->levels->count())
                                <div class="table_levels">
                                    @if($category->levels->count() > 1)
                                    <div class="whitefade"></div>
                                    <style>
                                        #row<?php echo $category->id; ?> .table_levels:hover > div:not(.whitefade){
                                            border-bottom: 2px solid #ccc;
                                        }
                                    </style>
                                    @endif
                                    <div>
                                        @foreach($category->levels as $level)
                                        <a href='/categories/by_level/{{ $level->id }}'>- {{ $level->name }}</a><br/>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <strong>—</strong>
                                @endif
                            </td>
                            <td>
                                @if($category->status === 'published')
                                <span style='color: #72c01d;'>Опубликовано</span>
                                @elseif($category->status === 'draft')
                                Черновик
                                @elseif($category->status === 'scheduled')
                                Запланировано на:<br/><span class='toLocalTime' style='font-weight: 700'>{{ getDateTime($category->scheduled) }}</span>
                                    @if($category->comingsoon)
                                    <br/>
                                    - Отображать дату
                                    @endif
                                @elseif($category->status === 'scheduled2')
                                Запланировано (от даты регистрации пользователя):<br/>
                                через <strong>{{ $category->sch2num }} {{ $category->sch2typename }}</strong>
                                    @if($category->comingsoon)
                                    <br/>
                                    - Отображать дату
                                    @endif
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="{{ getPreviewLink('category', $category->id) }}" class="cab-icon cab-icon1" target="_blank"></a>
                                    <a href="/categories/{{ $category->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_category_delete" class="cab-icon cab-icon3 fancybox category-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @elseif($project->categories->count())
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">Нет категорий с данным уровнем доступа</div>
                <div class="add-project-button">
                    <a href="/categories" class="green-button">Ко всем категориям</a>
                </div>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет категорий</div>
                <div class="add-project-text">Создайте категорию и добавьте в неё<br> ваши публикации</div>
                <div class="add-project-button">
                    <a href="/categories/add" class="green-button">Создать новую категорию</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_category_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить категорию "<span></span>"?</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">При этом удалятся все публикации этой категории вместе с файлами и загруженными видео!</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.material-table .cab-icon.category-delete').on('click', function () {
            var id = $(this).closest("tr").data('id');
            var name = $(this).closest("tr").find(".table-title").text();

            $("#popup_category_delete a.red-button").attr('href', "/categories/" + id + "/delete");
            $("#popup_category_delete .popup-min-title span").text(name);
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
        
        var sort = "{{ Session::get('sort.categories.order_by') }}";
        $("a.table-sort[data-sort='"+sort+"']").css({'font-weight':'700', 'text-decoration':'underline'});
    });
</script>
@endsection