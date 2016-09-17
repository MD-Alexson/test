@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Новый пользователь</div>
            <div class="add-material">
                <form action="{{ action('Backend\SusersController@store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
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
                            <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20" value="{{ Request::old('password') }}">
                            <div class="material-note">Если не указать пароль, он будет сгенерирован автоматически</div>
                        </div>
                        <div class="add-mat-title">Доступ</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Уровень доступа</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="level_id">
                                    @foreach($project->levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Ограничение по времени</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                <div class="check-block">
                                    <input name="expire" type="checkbox" class="check" id="ch1">
                                    <label for="ch1" class="check-label">Ограничить доступ до даты:</label>
                                </div>
                            </div>
                            <div id="expires" style="display: none;">
                                <input type="text" class="input datetime" placeholder="Дата" name="expires" value="{{ Request::old('expires') }}">
                                <input type="hidden" name="offset" value="">
                            </div>
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
                            <button class="green-button float-left" type="submit" onclick="javascript: $(this).closest('form').find('input[name=send_data]').remove();">Сохранить</button>
                            <button class="green-button float-left" type="submit" onclick="javascript: $(this).closest('form').prepend('<input type=\'hidden\' name=\'send_data\' value=\'1\'>')" style="width: auto; padding: 0px 15px;">Сохранить и отправить данные доступа</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
</section>
<script type='text/javascript'>
    $(document).ready(function () {
        $("input[name=expire]").on('change', function(){
            if($(this).prop('checked')){
                $("#expires").slideDown(200);
            } else {
                $("#expires").slideUp(200);
            }
        });
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
@include('backend.inc.sidebar')
@endsection