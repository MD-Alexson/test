@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Импорт пользователей</div>
            <div class="add-material">
                <div class='my_tabs'>
                    <div class='my_tabs_controll'>
                        <a href='#tab1' class='green-button inline-button disabled' style='width: auto; padding-left: 15px; padding-right: 15px; margin-right: 10px;'>Импортировать вручную</a>
                        <a href='#tab2' class='white-button inline-button' style='width: auto; padding-left: 15px; padding-right: 15px;'>Импортировать таблицу CSV</a>
                    </div>
                    <form action='{{ action("Backend\SusersController@importManual") }}' method='post' class='tab active' id='tab1'>
                        <fieldset>
                            <div class="material-note">
                                Вы можете импортировать пользователей вручную, указав <strong>email, имя и телефон</strong> через запятую с каждой новой строки. Обазятельно - <strong>email</strong><br/>
                                Например:<br/>
                                <strong>test@gmail.com, Имя Фамилия, +998901111111<br/>
                                    test2@gmail.com, Имя Фамилия 2<br/>
                                    test3@gmail.com</strong>
                            </div>
                            <div class="add-mat-title">Данные импорта</div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Поле ввода</div>
                            </div>
                            <div class="add-mat-right">
                                <textarea cols="30" rows="10" class="textarea" placeholder="email@gmail.com, Имя Фамилия,  +998901111111" name="import"></textarea>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Уровень доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Уровень доступа который будет назначен импортируемым пользователям. При выборе 'Как в файле импорта' будет выбран уровень доступа с названием которое указано в файле импорта (поле <strong>level_name</strong>) а если при этом оно не указано, то уровень доступа созданный первым</div>">?</span></div>
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
                                <div class="add-mat-text">Пароль <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете указать пароль, который получат импортируемые пользователи. Если не указывать - будет исользован пароль указанный в файле импорта (поле <strong>password</strong>), а если он не указан, то стандратный (<strong>62256225</strong>)</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20" required="required" value='{{ str_random(8) }}'>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Ограничение по времени <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете ограничить доступ пользователям до определенной даты. Если не указывать - будут браться значения из файла импорта (поля <strong>expire</strong> и <strong>expires</strong>), а если они не установлены то значение по-умолчанию (<strong>Не ограничивать</strong>)</div></div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="check-list">
                                    <div class="check-block">
                                        <input name="expire" type="checkbox" class="check" id="ch2">
                                        <label for="ch2" class="check-label">Ограничить доступ до даты:</label>
                                    </div>
                                </div>
                                <div id="expires2" style="display: none;">
                                    <input type="text" class="input datetime" placeholder="Дата" name="expires" value="{{ Request::old('expires') }}">
                                    <input type="hidden" name="offset" value="">
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Статус <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Статус пользователей после импорта. Если не указывать - будет браться значение из файла импорта (поле <strong>status</strong>), а если оно не установлено то значение по-умолчанию (<strong>Активен</strong>)</div>">?</span></div>
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
                                <button class="green-button" type="submit">Импортировать</button>
                            </div>
                        </fieldset>
                    </form>
                    <form action="{{ action('Backend\SusersController@import') }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8" class='tab' id='tab2'>
                    {{ csrf_field() }}
                        <fieldset>
                            <div class="material-note">
                                Записи CSV файла импорта обязаны содержать обязательное уникальное поле <strong>email</strong>.<br/><br/>
                                Необязательные поля:<br/>
                                - <strong>name</strong> (Имя);<br/>
                                - <strong>phone</strong> (Номер телефона);<br/>
                                - <strong>password</strong> (Пароль);<br/>
                                - <strong>level_name</strong> (Название уровня доступа, существующего на выбранном проекте);<br/>
                                - <strong>expire</strong> (Ограничивать ли доступ пользователя до даты: <strong>1</strong> - да, <strong>0</strong> - нет);<br/>
                                - <strong>expires</strong> (Дата, до которой будет ограничен доступ пользователю по Гринвичу (UTC-0));<br/>
                                - <strong>status</strong> (Статус пользователя: <strong>1</strong> - активен, <strong>0</strong> - деактивирован)
                                <br/><br/>
                                <a href='{{ asset('assets/csv/sample.csv') }}' class='white-button' style="margin: 0px;">Скачать образец</a>
                            </div>
                            <div class="add-mat-title">Данные импорта</div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Файл импорта (CSV) <span class="required-title">*</span></div>
                            </div>
                            <div class="add-mat-right">
                                <label class="white-button inline-button" for="file" style="margin-right: 20px;">Загрузить файл</label>
                                <p class='image_path'></p>
                                <input type="file" name="file" id="file" style='display: none' required="required" accept=".csv">
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Уровень доступа <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Уровень доступа который будет назначен импортируемым пользователям. При выборе 'Как в файле импорта' будет выбран уровень доступа с названием которое указано в файле импорта (поле <strong>level_name</strong>) а если при этом оно не указано, то уровень доступа созданный первым</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="select-block">
                                    <select class="styled" name="level_id">
                                        <option value="0">Как в файле импорта</option>
                                        @foreach($project->levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Пароль <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете указать пароль, который получат импортируемые пользователи. Если не указывать - будет исользован пароль указанный в файле импорта (поле <strong>password</strong>), а если он не указан, то стандратный (<strong>62256225</strong>)</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <input type="text" class="input" placeholder="От 8 до 20 символов" name="password" minlength="8" maxlength="20">
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text">Ограничение по времени <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Вы можете ограничить доступ пользователям до определенной даты. Если не указывать - будут браться значения из файла импорта (поля <strong>expire</strong> и <strong>expires</strong>), а если они не установлены то значение по-умолчанию (<strong>Не ограничивать</strong>)</div></div>">?</span></div>
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
                                <div class="add-mat-text">Статус <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Статус пользователей после импорта. Если не указывать - будет браться значение из файла импорта (поле <strong>status</strong>), а если оно не установлено то значение по-умолчанию (<strong>Активен</strong>)</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <div class="select-block">
                                    <select class="styled" name="status">
                                        <option value="0">Как в файле импорта</option>
                                        <option value="11">Активен</option>
                                        <option value="10">Деактивирован</option>
                                    </select>
                                </div>
                            </div>
                            <div class="add-mat-right-holder">
                                <button class="green-button" type="submit">Импортировать</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>
<div id="popup_required" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Импорт пользователей</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">Вы не прикрепили CSV файл импорта!</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type='text/javascript'>
    $(document).ready(function () {
        $("#tab1 input[name=expire]").on('change', function(){
            if($(this).prop('checked')){
                $("#expires").slideDown(200);
            } else {
                $("#expires").slideUp(200);
            }
        });
        $("#tab2 input[name=expire]").on('change', function(){
            if($(this).prop('checked')){
                $("#expires2").slideDown(200);
            } else {
                $("#expires2").slideUp(200);
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

        $("input[name=file]").on('change', function () {
            $("p.image_path").text($(this).val());
        });

        $(".add-material #tab2 button[type=submit]").on('click', function(e){
            if(!$('input[name=file]').val().length){
                e.preventDefault();
                $.fancybox("#popup_required");
            }
        });
    });
</script>
@include('backend.inc.sidebar')
@endsection