@extends('admin.app')
@section('content')
<?php \Auth::guard('backend')->logout(); ?>
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('admin.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Новый пользователь</div>
            <div class="add-material">
                <form action="{{ action('Admin\UsersController@store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Имя </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите имя пользователя" name="name" required="required" maxlength="32" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Email </div>
                        </div>
                        <div class="add-mat-right">
                            <input type="email" class="input" placeholder="Введите email пользователя" name="email" required="required" maxlength="32" value="{{ Request::old('email') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Телефон</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите телефон пользователя" name="phone" maxlength="20" value="{{ Request::old('phone') }}">
                        </div>

                        <div class="add-mat-left">
                            <div class="add-mat-text">Пароль</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20" value="{{ Request::old('password') }}" required="required">
                        </div>
                        <div class="add-mat-title">Доступ</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Тариф</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="plan_id">
                                    <option value="0">14 дней</option>
                                    <option value="1">Базовый</option>
                                    <option value="2">Бизнес</option>
                                    <option value="3">PRO</option>
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Ограничение по времени</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input datetime" placeholder="Дата" name="expires" value="{{ Request::old('expires') }}">
                            <input type="hidden" name="offset" value="">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Статус</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="status">
                                    <option value="1">Активен</option>
                                    <option value="0">Деактивирован</option>
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
        $(".datetime").datetimepicker({
            format:'d.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate:new Date(),
            minTime:new Date(),
            defaultDate:new Date()
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;

        $("input[name=offset]").val(offset);
    });
</script>
@include('admin.inc.sidebar')
@endsection