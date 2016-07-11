@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($project->homeworks->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Выполненные домашние задания к публикации {{ $post->name }}</div>
                    <div class="project-top-num">{{ $post->homeworks->count() }}</div>
                </div>
            </div>
            <div class="content-back">
                <a href="/homeworks">К списку всех ДЗ</a>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\HomeworksController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="delete">Удалить</option>
                                <option value="checked">Изменить статус</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_checked">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="checked">
                                <option value="1">Принято</option>
                                <option value="0">Не принято</option>
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
                        <col width="21%">
                        <col width="20%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td>Пользователь</td>
                            <td>Публикация</td>
                            <td>Выполненное задание</td>
                            <td>Статус</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($homeworks as $homework)
                        <tr class="row" data-id="{{ $homework->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td><a href='/users/{{ $homework->suser->id }}/edit'>{{ $homework->suser->name }}</a></td>
                            <td>{{ $homework->post->name }}</td>
                            <td>
                                <div class='short' id='short{{ $homework->id }}'>
                                    <?php echo nl2br($homework->text); ?>
                                </div>
                                @if(!empty($homework->file_path))
                                <br/>
                                <br/>
                                <p style="font-weight: 700">
                                    Прикрепленный файл ({{ formatBytes(Storage::size($homework->file_path)) }}):
                                    <a href="{{ pathTo($homework->file_path, 'filepath') }}">{{ $homework->file_name }}</a>
                                </p>
                                @endif
                            </td>
                            <td>
                                @if($homework->checked)
                                <span style="color: #72c01d">Принято</span>
                                @else
                                <span style="color: #c00">Не принято</span>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    @if($homework->checked)
                                    <a href="/homeworks/{{ $homework->id }}/uncheck" class="cab-icon cab-icon5"></a>
                                    @else
                                    <a href="/homeworks/{{ $homework->id }}/check" class="cab-icon cab-icon4"></a>
                                    @endif
                                    <a href="/homeworks/{{ $homework->id }}/delete" class="cab-icon cab-icon3"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @include('backend.inc.pagination', ['entities' => $homeworks])
            </div>
            @else
            <div class="add-project">
                <div class="add-project-title">Пока нет выполненных домашних заданий к данной публикации</div>
                <div class="add-project-button">
                    <a href="/homeworks" class="green-button fancybox">К списку всех ДЗ</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<script type='text/javascript'>
    function homeworkToggle(id){
        var short = $("#short"+id);
        var link = $("#short"+id+"link");
        if(short.hasClass('open')){
            short.removeClass('open');
            link.text("Показать полностью");
        } else {
            short.addClass('open');
            link.text("Спрятать");
        }
    }
    $(document).ready(function(){
        $(".material-table table tr").each(function(){
            var short = $(this).find("div.short");
            if(short.outerHeight() > 100){
                short.after("<a href='javascript: homeworkToggle("+$(this).data('id')+")' id='short"+$(this).data('id')+"link'>Показать полностью</a>");
            }
        });
    });
</script>
@endsection