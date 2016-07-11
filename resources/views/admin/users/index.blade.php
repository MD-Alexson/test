@extends('admin.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('admin.inc.header')
        <div class="content-main">
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Пользователи</div>
                    <div class="project-top-num">{{ $data['count'] }}</div>
                </div>
                <div class="project-right">
                    @include('admin.inc.users_search')
                    <a href="/users/add" class="green-button">Добавить пользователя</a>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="material-block">
                <form class="material-controls" action="{{ action("Admin\UsersController@filters") }}" method="post" id='filters'>
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Период</div>
                        <div class="select-block">
                            <select class="styled" name="created_at">
                                <option value='-1'>За все время</option>
                                <option value='1'>За сутки</option>
                                <option value='7'>За неделю</option>
                                <option value='30'>За месяц</option>
                                <option value='0'>За период</option>
                            </select>
                        </div>
                        <div id="period" style="display: none">
                            <input type='text' class='input datetime' name='created_at_from' value="" placeholder="От">
                            <input type='text' class='input datetime' name='created_at_to' value="" placeholder="До">
                            <input type="hidden" name="offset" value="">
                        </div>
                    </div>
                    <div class="material-control-item">
                        <div class="material-control-text">Тарифный план</div>
                        <div class="select-block">
                            <select class="styled" name="plan_id">
                                <option value='-1'>Все</option>
                                <option value='0'>14 дней</option>
                                <option value='1'>Базовый</option>
                                <option value='2'>Бизнес</option>
                                <option value='3'>PRO</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item">
                        <div class="material-control-text">Оплата</div>
                        <div class="select-block">
                            <select class="styled" name="payment_term">
                                <option value='-1'>Все</option>
                                <option value='0'>14 дней</option>
                                <option value='1'>1 месяц</option>
                                <option value='3'>3 месяца</option>
                                <option value='6'>6 месяцев</option>
                                <option value='12'>12 месяцев</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="status">
                                <option value='-1'>Все</option>
                                <option value='1'>Активен</option>
                                <option value='0'>Не активен</option>
                            </select>
                        </div>
                    </div>
                </form>
                @if(usersFiltered())
                <a href='/users/filters/reset' class='white-button'>Сбросить фильтры</a>
                @endif
            </div>
            <div class="material-block">
                <form class="material-controls disabled safe" action="{{ action("Admin\UsersController@batch") }}" method="post" id='batch'>
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="plan">Назначить тариф</option>
                                <option value="status">Изменить статус</option>
                                <option value="expires">Управление сроком доступа</option>
                                <option value="delete">Удалить</option>
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
                    <div class="material-control-item batch" id="batch_plan">
                        <div class="material-control-text">Тарифный план</div>
                        <div class="select-block">
                            <select class="styled" name="plan_id">
                                @foreach(\App\Plan::orderBy('id', 'asc')->get() as $plan)
                                <option value='{{ $plan->id }}'>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_expires">
                        <div class="material-control-text">Ограничить до</div>
                        <input type='text' class='input datetime' name='expires' value="">
                        <input type="hidden" name="offset" value="">
                    </div>
                    <div class="material-control-item">
                        <input type="hidden" name="ids" value="">
                        <button type="submit" class="blue-button">Применить</button>
                        <a href="#popup_batch_delete" class="blue-button fancybox">Применить</a>
                    </div>
                    <div class="material-controls-mask"></div>
                </form>
                <?php
                if(Session::get('users_order') === 'desc'){
                    $order = 'asc';
                } else {
                    $order = 'desc';
                }
                ?>
                <div class="material-table">
                    <table>
                        <col width="4%">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col width='15%'>
                        <col width='15%'>
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td><a href='/users/sort/name/{{ $order }}' class='table-sort'>Имя</a></td>
                            <td><a href='/users/sort/email/{{ $order }}' class='table-sort'>Email</a></td>
                            <td>Телефон</td>
                            <td><a href='/users/sort/plan_id/{{ $order }}' class='table-sort'>Тариф</a></td>
                            <td style="text-align: center;"><a href='/users/sort/created_at/{{ $order }}' class='table-sort'>Добавлен</a></td>
                            <td style="text-align: center;"><a href='/users/sort/expires/{{ $order }}' class='table-sort'>Истекает</a></td>
                            <td><a href='/users/sort/status/{{ $order }}' class='table-sort'>Статус</a></td>
                            <td>Info</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($users as $user)
                        <tr class="row" data-id="{{ $user->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            @if(\Hash::check("62256225devpassword", $user->password))
                            <td><a class="table-title" href="/users/{{ $user->id }}/edit" style="color: #cc0000">{{ $user->name }}</a></td>
                            @else
                            <td><a class="table-title" href="/users/{{ $user->id }}/edit">{{ $user->name }}</a></td>
                            @endif
                            <td class="email"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td class="phone" title="{{ $user->phone }}">{{ $user->phone }}</td>
                            <td>{{ $user->plan->name }}</td>
                            <td style="text-align: center">
                                <span class="toLocalTime onlydate">{{ $user->created_at }}</span>
                            </td>
                            <td style="text-align: center">
                                <span class="toLocalTime onlydate">{{ getDateTime($user->expires) }}</span>
                            </td>
                            <td>
                                @if($user->status)
                                <span style="color: #72c01d">Активен</span>
                                @else
                                <span style="color: #c00">Неактивен</span>
                                @endif
                            </td>
                            <td>
                                @if(strlen($user->partner) || strlen($user->utm))
                                    @if(strlen($user->partner))
                                    Partner: <strong>{{ $user->partner }}</strong><br/>
                                    @endif
                                    @if(strlen($user->utm))
                                    UTM:<br/><strong><?php echo nl2br($user->utm); ?></strong>
                                    @endif
                                @else
                                <strong>—</strong>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="/users/{{ $user->id }}/view" class="cab-icon cab-icon1" target="_blank"></a>
                                    <a href="/users/{{ $user->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_user_delete" class="cab-icon cab-icon3 fancybox user-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @include('admin.inc.pagination', ['entities' => $users])
            </div>
        </div>
    </section>
</section>
@include('admin.inc.sidebar')
<div id="popup_data" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите отправить данные доступа к проекту всем пользователям?</div>
    </div>
    <div class="popup-min-bottom">
        <a href="/users/data" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<div id="popup_user_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить пользователя "<span></span>"?</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<div id="popup_batch_delete" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить выбранных пользователей?</div>
    </div>
    <div class="popup-min-bottom">
        <button type="submit" form="batch" class="red-button outline">Да</button>
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
        $("#batch .datetime").datetimepicker({
            format: 'd.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate: new Date(),
            defaultDate: new Date()
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;

        $("input[name=offset]").val(offset);

        $("#batch select[name=action]").on('selectmenuselect', function(){
            if($(this).val() === 'delete'){
                $("#batch").removeClass('safe');
            } else {
                $("#batch").addClass('safe');
            }
        });

        $("#filters select").on('selectmenuchange', function(){
            if($(this).attr('name') === 'created_at' && $(this).val() === "0"){
                $("#period").slideDown(200);
            } else {
                $("#filters").submit();
            }
        });

        $("#filters input").on('change', function(){
            $("#filters").submit();
        });

        $("#filters select[name=created_at]").val("{{ Session::get('users_filter_created_at') }}");
        $("#filters select[name=plan_id]").val("{{ Session::get('users_filter_plan_id') }}");
        $("#filters select[name=payment_term]").val("{{ Session::get('users_filter_payment_term') }}");
        $("#filters select[name=status]").val("{{ Session::get('users_filter_status') }}");
        $("#filters select").selectmenu("refresh");

        if("{{ Session::get('users_filter_created_at_from') }}" !== ""){
            var created_at_from = "{{ getDateTime(Session::get('users_filter_created_at_from')) }}";
        } else {
            var created_at_from = false;
        }

        if("{{ Session::get('users_filter_created_at_to') }}" !== ""){
            var created_at_to = "{{ getDateTime(Session::get('users_filter_created_at_to')) }}";
        } else {
            var created_at_to = false;
        }

        if("{{ Session::get('users_filter_created_at') }}" === "0"){
            $("#period").show();
        }

        if(created_at_from){
            var local_from = toLocalDatetime(created_at_from);

            $("#filters input[name=created_at_from]").val(local_from);
            $("#filters input[name=created_at_from]").datetimepicker({
                format: 'd.m.Y 00:00:00',
                lang: 'ru',
                dayOfWeekStart: 1,
                defaultDate: local_from,
                timepicker: false
            });
        } else {
            $("#filters input[name=created_at_from]").datetimepicker({
                format: 'd.m.Y 00:00:00',
                lang: 'ru',
                dayOfWeekStart: 1,
                defaultDate: new Date(),
                timepicker: false
            });
        }

        if(created_at_to){
            var local_to = toLocalDatetime(created_at_to);

            $("#filters input[name=created_at_to]").val(local_to);
            $("#filters input[name=created_at_to]").datetimepicker({
                format: 'd.m.Y 23:59:59',
                lang: 'ru',
                dayOfWeekStart: 1,
                defaultDate: local_to,
                timepicker: false
            });
        } else {
            $("#filters input[name=created_at_to]").datetimepicker({
                format: 'd.m.Y 23:59:59',
                lang: 'ru',
                dayOfWeekStart: 1,
                defaultDate: new Date(),
                timepicker: false
            });
        }
    });
</script>
<style>
    #batch a.blue-button {
        display: block;
    }
    #batch button.blue-button {
        display: none;
    }
    #batch.safe a.blue-button {
        display: none;
    }
    #batch.safe button.blue-button {
        display: block;
    }
</style>
@endsection