@extends('admin.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('admin.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Редактировать пользователя "{{ $user->name }}"</div>
            <div class="add-material">
                <form action="{{ action('Admin\UsersController@update', ['user_id' => $user->id]) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Имя </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите имя пользователя" name="name" required="required" maxlength="32" value="{{ $user->name }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Email </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="email" class="input" placeholder="Введите email пользователя" name="email" required="required" maxlength="32" value="{{ $user->email }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Телефон</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите телефон пользователя" name="phone" maxlength="20" value="{{ $user->phone }}">
                        </div>

                        <div class="add-mat-left">
                            <div class="add-mat-text">Пароль (обновить, не обязательно)</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20" value="{{ $user->password_raw }}">
                        </div>
                        <div class="add-mat-title">Доступ</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Уровень доступа</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="plan_id">
                                    @foreach(\App\Plan::orderBy('id', 'asc')->get() as $plan)
                                    @if($user->plan_id === $plan->id)
                                    <option value="{{ $plan->id }}" selected="selected">{{ $plan->name }}</option>
                                    @else
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Ограничение по времени</div>
                        </div>
                        <div class="add-mat-right">
                            <div id="expires">
                                <input type="text" class="input datetime" placeholder="Дата" name="expires" value="">
                                <input type="hidden" name="offset" value="">
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Статус</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="status">
                                    @if((int) $user->status)
                                    <option value="1" selected="selected">Активен</option>
                                    <option value="0">Деактивирован</option>
                                    @else
                                    <option value="1">Активен</option>
                                    <option value="0" selected="selected">Деактивирован</option>
                                    @endif
                                </select>
                            </div>
                        </div>
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
    $(document).ready(function () {
        var datetime = "{{ getDateTime($user->expires) }}";
        var local = toLocalDatetime(datetime);
        $("input.datetime").val(local);
        $(".datetime").datetimepicker({
            format:'d.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate:new Date(),
            defaultDate:local
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;
        $("input[name=offset]").val(offset);
    });
</script>
@include('admin.inc.sidebar')
@endsection