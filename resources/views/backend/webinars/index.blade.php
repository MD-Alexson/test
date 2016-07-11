@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($webinars->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Все вебинары</div>
                    <div class="project-top-num">{{ $webinars->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/webinars/add" class="green-button">Добавить вебинар</a>
                </div>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\WebinarsController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="status">Изменить статус</option>
                                <option value="delete">Удалить</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_status">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="status">
                                <option value="1">Опубликовано</option>
                                <option value="0">Черновик</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item">
                        <input type="hidden" name="ids" value="">
                        <button type="submit" class="blue-button">Применить</button>
                    </div>
                    <div class="material-controls-mask"></div>
                </form>
                <div class="material-table">
                    <table>
                        <col width="4%">
                        <col width="30%">
                        <col width="26%">
                        <col width="30%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td>Название</td>
                            <td>Url</td>
                            <td>Статус</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($webinars as $webinar)
                        <tr class="row" data-id="{{ $webinar->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td>
                                <a class="table-title" href="/webinars/{{ $webinar->id }}/edit">{{ $webinar->name }}</a>
                            </td>
                            <td class="url">
                                <a href='{{ getPreviewLink('webinar', $webinar->id) }}' target="_blank">{{ getPreviewLink('webinar', $webinar->id) }}</a>
                            </td>
                            <td>
                                @if($webinar->status)
                                <span style='color: #72c01d;'><span class='toLocalTime' style='font-weight: 700'>{{ getDateTime($webinar->date) }}</span></span>
                                @else
                                <span style='color: #cc0000;'>Черновик</span>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="http://{{ $project->domain }}.{{ config('app.domain') }}/webinar/{{ $webinar->url }}" class="cab-icon cab-icon1" target="_blank"></a>
                                    <a href="/webinars/{{ $webinar->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_webinar_delete" class="cab-icon cab-icon3 fancybox webinar-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет вебинаров</div>
                <div class="add-project-text">Запланируйте и настройте<br/>проведение вашего вебинара</div>
                <div class="add-project-button">
                    <a href="/webinars/add" class="green-button">Создать новый вебинар</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_webinar_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить вебинар "<span></span>"?</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.material-table .cab-icon.webinar-delete').on('click', function () {
            var id = $(this).closest("tr").data('id');
            var name = $(this).closest("tr").find(".table-title").text();

            $("#popup_webinar_delete a.red-button").attr('href', "/webinars/" + id + "/delete");
            $("#popup_webinar_delete .popup-min-title span").text(name);
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
@endsection