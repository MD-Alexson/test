@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/users">Вернуться к списку пользователей</a>
            </div>
            <div class="content-title">Экспорт пользователей</div>
            <div class="add-material">
                <div class="material-note">
                    Пользователи будут экспортированы в CSV или XLS файл со следующими полями:<br/><br/>
                    - <strong>email</strong> (E-Mail);<br/>
                    - <strong>name</strong> (Имя);<br/>
                    - <strong>phone</strong> (Номер телефона);<br/>
                    - <strong>password</strong> (Пароль);<br/>
                    - <strong>level_name</strong> (Название уровня доступа);<br/>
                    - <strong>expire</strong> (Ограничивать ли доступ пользователя до даты: <strong>1</strong> - да, <strong>0</strong> - нет);<br/>
                    - <strong>expires</strong> (Дата, до которой будет ограничен доступ пользователю по Гринвичу (UTC-0));<br/>
                    - <strong>status</strong> (Статус пользователя: <strong>1</strong> - активен, <strong>0</strong> - деактивирован)
                </div>
                <a href='/users/export/csv' class='green-button inline-button' style='width: auto; padding-left: 15px; padding-right: 15px; margin-right: 10px;'>Экспорт пользователей проекта в CSV</a>
                <a href='/users/export/xls' class='green-button inline-button' style='width: auto; padding-left: 15px; padding-right: 15px;'>Экспорт пользователей проекта в XLS</a>
            </div>
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
@endsection