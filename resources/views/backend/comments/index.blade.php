@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($project->comments->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Комментарии</div>
                    <div class="project-top-num">{{ $project->comments->count() }}</div>
                </div>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\CommentsController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="delete">Удалить</option>
                                <option value="allowed">Изменить статус</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item batch" id="batch_allowed">
                        <div class="material-control-text">Статус</div>
                        <div class="select-block">
                            <select class="styled" name="allowed">
                                <option value="1">Опубликован</option>
                                <option value="0">Не опубликован</option>
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
                            <td>Комментарий</td>
                            <td>Статус</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($comments as $comment)
                        @if($comment->commentable_type === 'App\Suser')
                        <?php $user = \App\Suser::findOrFail($comment->commentable_id); $link = '/users/'.$user->id.'/edit'; $style=""; ?>
                        @else
                        <?php $user = \App\User::findOrFail($comment->commentable_id); $link = '/account'; $style="color: #72c01d"; ?>
                        @endif
                        <tr class="row" data-id="{{ $comment->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td><a href='{{ $link }}' style='{{ $style }}'>{{ $user->name }}</a></td>
                            <td><a href="/comments/by_post/{{ $comment->post->id }}">{{ $comment->post->name }}</a></td>
                            <td>
                                <div class='short' id='short{{ $comment->id }}'>
                                    <?php echo nl2br($comment->text); ?>
                                </div>
                            </td>
                            <td>
                                @if($comment->allowed)
                                <span style="color: #72c01d">Опубликован</span>
                                @else
                                <span style="color: #c00">Не опубликован</span>
                                @endif
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    @if($comment->allowed)
                                    <a href="/comments/{{ $comment->id }}/disable" class="cab-icon cab-icon5"></a>
                                    @else
                                    <a href="/comments/{{ $comment->id }}/allow" class="cab-icon cab-icon4"></a>
                                    @endif
                                    <a href="/comments/{{ $comment->id }}/delete" class="cab-icon cab-icon3"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @include('backend.inc.pagination', ['entities' => $comments])
            </div>
            @else
            <div class="add-project">
                <div class="add-project-title">Пока нет комментариев</div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<script type='text/javascript'>
    function commentToggle(id){
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
                short.after("<a href='javascript: commentToggle("+$(this).data('id')+")' id='short"+$(this).data('id')+"link'>Показать полностью</a>");
            }
        });
    });
</script>
@endsection