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
                <form action="{{ action('Backend\IprController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Название</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите название" name="name" required="required" maxlength="40" value="{{ Request::old('name') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Тестовый ключ <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Будет использоваться для вас при предпросмотре проекта</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите тестовый ключ" name="test_key" minlength="14" maxlength="14" value="{{ Request::old('test_key') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Ключи активаций</div>
                        </div>
                        <div class="add-mat-right">
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
<script type='text/javascript'>
    $(document).ready(function () {
        $("input[name=new_keys]").on('change', function () {
            $("p.new_keys_path").text($(this).val());
        });
    });
</script>
@include('backend.inc.sidebar')
@endsection