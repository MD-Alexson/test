@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($posts->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Порядок публикаций категории "{{ $category->name }}"</div>
                    <div class="project-top-num">{{ $posts->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/posts/add" class="green-button">Добавить публикацию</a>
                </div>
            </div>
            <div class="content-back">
                <a href="/posts/by_category/{{ $category->id }}">К списку публикаций категории</a>
            </div>
            <div class="material-block">
                <ol class="sortable">
                    @foreach($posts as $post)
                    <li id="list_{{ $post->id }}" data-id="{{ $post->id }}">
                        <div>{{ $post->name }}</div>
                    </li>
                    @endforeach
                </ol>
                <br/>
                <a href="javascript: order();" class="green-button">Сохранить</a>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет публикаций<br/>в данной категории</div>
                <div class="add-project-text">Создайте новую публикацию</div>
                <div class="add-project-button">
                    <a href="/posts/add" class="green-button">Создать публикацию</a>
                </div>
                <a href="javascript: history.back()">Назад</a>
            </div>
            @endif
        </div>
    </section>
</section>
<div id="popup_ok" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-big-icon"><img src="{{ asset('assets/images/ok.png') }}" alt=""></div>
        <div class="popup-min-title">Порядок публикаций</div>
        <div class="popup-min-text">Вы успешно установили последовательность публикаций в категории "{{ $category->name }}"!</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
@include('backend.inc.sidebar')
<script type="text/javascript">
    function order(){
        var nestedArray = $('.sortable').nestedSortable('serialize');
        $.ajax({
            url: '/posts/by_category/{{ $category->id }}/order_change/?' + nestedArray,
            success: function (data) {
                $.fancybox("#popup_ok");
            }
        });
    }
    $(document).ready(function () {
        $(".sortable").nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 1
        });
    });
</script>
@endsection