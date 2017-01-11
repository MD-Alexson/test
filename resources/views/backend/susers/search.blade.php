@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($data['count'])
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Поиск: "{{ $data['query'] }}"</div>
                    <div class="project-top-num">{{ $data['count'] }}</div>
                </div>
                <div class="project-right">
                    @include('backend.inc.susers_search')
                    <a href="/users/add" class="green-button">Добавить пользователя</a>
                </div>
            </div>
            <div class="content-back" style="float: left">
                <a href="/users">К списку всех пользователей</a>
            </div>
            <div style="clear: both"></div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\SusersController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="delete">Удалить</option>
                                <option value="level">Назначить уровень доступа</option>
                                <option value="status">Изменить статус</option>
                                <option value="expire">Управление сроком доступа</option>
                                <option value="data">Отправить доступ</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_status">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="status">
                                <option value="1">Активен</option>
                                <option value="0">Деактивирован</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_level">
                        <div class="material-control-text">Уровень доступа</div>
                        <div class="select-block">
                            <select class="styled" name="level">
                                @foreach($project->levels as $level)
                                <option value='{{ $level->id }}'>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_expire">
                        <div class="material-control-text">Ограничивать срок</div>
                        <div class="select-block">
                            <select class="styled" name="expire">
                                <option value="0">Не ограничивать</option>
                                <option value="1">Ограничить</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch2" id="batch2_expire_1">
                        <div class="material-control-text">Ограничить до</div>
                        <input type='text' class='input datetime' name='expires' value="">
                        <input type="hidden" name="offset" value="">
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
                        <col width="16%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                        <col width="12%">
                        <col width="13%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td>Имя</td>
                            <td>Email</td>
                            <td>Телефон</td>
                            <td>Уровень доступа</td>
                            <td>Статус</td>
                            <td style="text-align: center;">Дата добавления</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($users as $user)
                        <tr class="row" data-id="{{ $user->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td><a class="table-title" href="/users/{{ $user->id }}/edit">{{ $user->name }}</a></td>
                            <td class="email"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td class="phone">{{ $user->phone }}</td>
                            <td><a href="/users/by_level/{{ $user->level->id }}">{{ $user->level->name }}</a></td>
                            <td>
                                @if($user->expire)
                                До: <span class="toLocalTime">{{ getDateTime($user->expires) }}</span>
                                @endif
                                @if($user->status)
                                <span style="color: #72c01d">Активен</span>
                                @else
                                <span style="color: #c00">Деактивирован</span>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <span class="toLocalTime onlydate">{{ $user->created_at }}</span>
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="/users/{{ $user->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_user_delete" class="cab-icon cab-icon3 fancybox user-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @include('backend.inc.pagination', ['entities' => $users, 'query' => $data['query']])
            </div>
            @else
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Результаты поиска</div>
                    <div class="project-top-num">{{ $data['count'] }}</div>
                </div>
                <div class="project-right">
                    @include('backend.inc.susers_search')
                    <a href="/users/add" class="green-button">Добавить пользователя</a>
                </div>
            </div>
            <div class='material-block'>
                <p>Поиск не дал результатов</p>
                <a href='javascript: history.back()' class='white-button'>Назад</a>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_user_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить пользователя "<span></span>"?</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.material-table .cab-icon.user-delete').on('click', function () {
            var id = $(this).closest("tr").data('id');
            var name = $(this).closest("tr").find(".table-title").text();

            $("#popup_user_delete a.red-button").attr('href', "/users/" + id + "/delete");
            $("#popup_user_delete .popup-min-title span").text(name);
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