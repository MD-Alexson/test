@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Редактировать пользователя "{{ $user->name }}"</div>
            <div class="add-material">
                <form action="{{ action('Backend\SusersController@update', ['user_id' => $user->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
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
                            <div class="add-mat-text">Пароль</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20" value="{{ $user->password_raw }}" required="required">
                        </div>
                        <div class="add-mat-title">Доступ</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Уровень доступа</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="level_id">
                                    @foreach($project->levels as $level)
                                    @if($user->level_id === $level->id)
                                    <option value="{{ $level->id }}" selected="selected">{{ $level->name }}</option>
                                    @else
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endif
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
                                    @if($user->expire)
                                    <input name="expire" type="checkbox" class="check" id="ch1" checked="checked">
                                    @else
                                    <input name="expire" type="checkbox" class="check" id="ch1">
                                    @endif
                                    <label for="ch1" class="check-label">Ограничить доступ до даты:</label>
                                </div>
                            </div>
                            @if($user->expire)
                            <div id="expires">
                            @else
                            <div id="expires" style="display: none;">
                            @endif
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
                        <div class="add-mat-left">
                            <div class="add-mat-text">Уникальный код</div>
                        </div>
                        <div class="add-mat-right">
                            <p>{{ $user->rand }}</p>
                        </div>
                            @if($project->ipr_levels()->count())
                        <div class="add-mat-title">InfoProtector™</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Уровень</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                @foreach($project->ipr_levels as $ipr_level)
                                <div class="check-block">
                                    <input data-id='{{ $ipr_level->id }}' name="ipr_levels[{{ $ipr_level->id }}]" type="checkbox" class="check check-level" id="ipr_lvl{{ $ipr_level->id }}">
                                    <label for="ipr_lvl{{ $ipr_level->id }}" class="check-label"><strong>{{ $ipr_level->name }}:</strong></label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
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
        
        @if($user->ipr_levels->count())
            @foreach($user->ipr_levels as $ipr_level)
            $('input[name="ipr_levels[{{ $ipr_level->id }}]"]').prop('checked', true);
            @endforeach
            $.uniform.update();
        @endif

    @if (Request::old('appearing'))
            $("select[name=appearing] option[value=1]").prop('selected', true);
    $("select[name=appearing]").selectmenu("refresh");
    @endif
    });
</script>
@include('backend.inc.sidebar')
@endsection