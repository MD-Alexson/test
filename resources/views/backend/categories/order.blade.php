@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($categories->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Порядок категорий</div>
                    <div class="project-top-num">{{ $categories->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/categories/add" class="green-button">Добавить категорию</a>
                </div>
            </div>
            <div class="content-back">
                <a href="/categories">К списку всех категорий</a>
            </div>
            <div class="material-block">
                <ol class="sortable">
                    @foreach($categories as $category)
                    <li id="list_{{ $category->id }}" data-id="{{ $category->id }}">
                        <div>{{ $category->name }}</div>
                    </li>
                    @endforeach
                </ol>
                <br/>
                <a href="javascript: order();" class="green-button">Сохранить</a>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-publications.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет категорий</div>
                <div class="add-project-text">Создайте категорию и добавьте в неё<br> ваши публикации</div>
                <div class="add-project-button">
                    <a href="/categories/add" class="green-button">Создать новую категорию</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
<div id="popup_ok" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-big-icon"><img src="{{ asset('assets/images/ok.png') }}" alt=""></div>
        <div class="popup-min-title">Порядок категорий</div>
        <div class="popup-min-text">Вы успешно установили последовательность категорий!</div>
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
            url: '/categories/order_change/?' + nestedArray,
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