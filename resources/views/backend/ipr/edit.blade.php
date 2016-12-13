@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/ipr">Вернуться</a>
            </div>

            <div class="add-material">
                <form action="{{ action('Backend\IprController@update', ['ipr_level_id' => $ipr_level->id]) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Название</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите название" name="name" required="required" maxlength="40" value="{{ $ipr_level->name }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Тестовый ключ <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Будет использоваться для вас при предпросмотре проекта</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите тестовый ключ" name="test_key" minlength="14" maxlength="14" value="{{ $ipr_level->test_key }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Ключи активаций</div>
                        </div>
                        <div class="add-mat-right">
                            <a href="#popup_check_keys" class="blue-button inline-button fancybox" style="margin-right: 10px;">Просмотреть ключи</a>
                            <label class="white-button inline-button" for="new_keys" style="margin-right: 20px;">Загрузить ключи</label>
                            <p class='new_keys_path'></p>
                            <input type="file" name="new_keys" id="new_keys" style='display: none' accept="text/plain">
                            <p class="caption" style='font-weight: 700'>.txt файл в котором каждый ключ будет с новой строки без разделителей и других данных.<br/>Ключи должны быть формата AAAA-AAAA-AAAA, ровно 14 символов</p>
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
<div id="popup_check_keys" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title" style='text-align: center'>Неприсвоенные ключи ({{ $ipr_level->ipr_keys()->count() }}):</div>
    </div>
    <div class="popup-min-content">
        <ul>
            @foreach($ipr_level->ipr_keys()->get() as $ipr_key)
            <li>{{ $ipr_key->key }}</li>
            @endforeach
        </ul>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type='text/javascript'>
    $(document).ready(function () {
        $("input[name=new_keys]").on('change', function () {
            $("p.new_keys_path").text($(this).val());
        });
    });
</script>
@include('backend.inc.sidebar')
@endsection