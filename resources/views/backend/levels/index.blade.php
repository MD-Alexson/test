@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($project->levels->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Уровни доступа</div>
                    <div class="project-top-num">{{ $project->levels->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="#popup_level_add" class="green-button fancybox" style="width: auto; padding-left: 15px; padding-right: 15px;">Добавить уровень доступа</a>
                </div>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\LevelsController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="delete">Удалить</option>
                            </select>
                        </div>
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
                        <col width="28%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="28%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td>Название</td>
                            <td style="text-align: center">Категории</td>
                            <td style="text-align: center">Публикации</td>
                            <td style="text-align: center">Пользователи</td>
                            <td style="text-align: center">Свойства</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($project->levels()->orderBy('id', 'asc')->get() as $level)
                        <tr class="row" data-id="{{ $level->id }}" data-open="{{ (int) $level->open }}" data-hidden="{{ (int) $level->hidden }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td><a href="#popup_level_edit" class="table-title fancybox level-edit">{{ $level->name }}</a></td>
                            <td style="text-align: center"><a href="/categories/by_level/{{ $level->id }}">{{ $level->categories->count() }}</a></td>
                            <td style="text-align: center"><a href="/posts/by_level/{{ $level->id }}">{{ $level->posts->count() }}</a></td>
                            <td style="text-align: center"><a href="/users/by_level/{{ $level->id }}">{{ $level->susers->count() }}</a></td>
                            <td style="text-align: center">
                                @if($level->open || $level->hidden)
                                    @if($level->open)
                                    - Разрешить регистрацию пользователям<br/>
                                    @endif
                                    @if($level->hidden)
                                    - Спрятать название уровня доступа от пользователей
                                    @endif
                                @else
                                <strong>—</strong>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="#popup_level_edit" class="cab-icon cab-icon2 fancybox level-edit"></a>
                                    <a href="#popup_level_delete" class="cab-icon cab-icon3 fancybox level-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-users.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет уровней доступа</div>
                <div class="add-project-text">Создайте первый и назначте его<br> вашим пользователям</div>
                <div class="add-project-button">
                    <a href="#popup_level_add" class="green-button fancybox">Создать уровень доступа</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_level_add" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Добавить уровень доступа</div>
    </div>
    <div class="popup-min-content">
        <div class='popup-min-text'>
            <form action='{{ action('Backend\LevelsController@store') }}' method="post" id='form_level_add'>
                {{ csrf_field() }}
                <input type="text" class="input" placeholder="Введите название" name="name" required="required" maxlength="255">
                <div class="check-list">
                    <div class="check-block">
                        <input name="open" type="checkbox" class="check" id="ch1">
                        <label for="ch1" class="check-label">Разрешить регистрацию пользователям</label>
                    </div>
                    <div class="check-block">
                        <input name="hidden" type="checkbox" class="check" id="ch2">
                        <label for="ch2" class="check-label">Спрятать название уровня доступа от пользователей</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="popup-min-bottom">
        <input type='submit' form="form_level_add" class="green-button" value='Добавить'>
        <button class="white-button close">Отмена</button>
    </div>
</div>
<div id="popup_level_edit" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title">Настройки уровня доступа</div>
    </div>
    <div class="popup-min-content">
        <div class='popup-min-text'>
            <form action='' method="post" id='form_level_edit'>
                {{ csrf_field() }}
                <input type="text" class="input" placeholder="Введите название" name="name" required="required" maxlength="255">
                <div class="check-list">
                    <div class="check-block">
                        <input name="open" type="checkbox" class="check" id="ch3">
                        <label for="ch3" class="check-label">Разрешить регистрацию пользователям</label>
                    </div>
                    <div class="check-block">
                        <input name="hidden" type="checkbox" class="check" id="ch4">
                        <label for="ch4" class="check-label">Спрятать название уровня доступа от пользователей</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="popup-min-bottom">
        <input type='submit' form="form_level_edit" class="green-button" value='Сохранить'>
        <button class="white-button close">Отмена</button>
    </div>
</div>
<div id="popup_level_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить уровень доступа "<span></span>"?</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">При этом удалятся все пользователи с этим уровнем!</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $('.material-table .cab-icon.level-delete').on('click', function(){
        var id = $(this).closest("tr").data('id');
        var name = $(this).closest("tr").find(".table-title").text();

        $("#popup_level_delete a.red-button").attr('href', "/levels/"+id+"/delete");
        $("#popup_level_delete .popup-min-title span").text(name);
    });
    $('.material-table .cab-icon.level-edit, .material-table .table-title').on('click', function(){
        $("#popup_level_edit input[name=open]").prop('checked', false);
        $("#popup_level_edit input[name=hidden]").prop('checked', false);
        $.uniform.update();

        var id = $(this).closest("tr").data('id');
        var name = $(this).closest("tr").find(".table-title").text();
        var open = parseInt($(this).closest("tr").data('open'));
        var hidden = parseInt($(this).closest("tr").data('hidden'));

        $("#popup_level_edit form").attr('action', "/levels/"+id+"/update");
        $("#popup_level_edit input[name=name]").val(name);

        if(open){
            $("#popup_level_edit input[name=open]").prop('checked', true);
            $.uniform.update();
        }

        if(hidden){
            $("#popup_level_edit input[name=hidden]").prop('checked', true);
            $.uniform.update();
        }
    });
    @if(Request::has('action') && Request::input('action') === 'add')
    $.fancybox("#popup_level_add");
    @endif
</script>
@endsection