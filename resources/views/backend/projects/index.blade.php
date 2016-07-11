@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if(Auth::guard('backend')->user()->projects->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Мои проекты</div>
                    <div class="project-top-num">{{ Auth::guard('backend')->user()->projects->count() }}</div>
                </div>
                <div class="project-right">
                    <!--
                    <div class="project-search">
                        <form action="">
                            <fieldset>
                                <input type="text" class="input" placeholder="Найти проект">
                                <button class="loupe"></button>
                            </fieldset>
                        </form>
                    </div>
                    -->
                    <a href="/projects/add" class="green-button">Добавить проект</a>
                </div>
            </div>
            @foreach(Auth::guard('backend')->user()->projects()->orderBy('created_at', 'desc')->get() as $project)
            <div class="project-new">
                <h1><a href="/select/{{ $project->domain }}/dashboard">{{ $project->name }}</a></h1>
                <div class='project-links'>
                    <a href='{{ getPreviewLink('project', $project->domain) }}' class='left' target="_blank">Предпросмотр</a>
                    <a href='/select/{{ $project->domain }}/settings' class='right'>Редактировать</a>
                </div>
                <div class='project-details'>
                    <a href='/select/{{ $project->domain }}/posts' class='project-details-holder'>
                        <div>
                            <span>Публикации</span>
                            <p>{{ $project->posts->count() }}</p>
                        </div>
                    </a>
                    <a href='/select/{{ $project->domain }}/categories' class='project-details-holder'>
                        <div>
                            <span>Категории</span>
                            <p>{{ $project->categories->count() }}</p>
                        </div>
                    </a>
                    <a href='/select/{{ $project->domain }}/levels' class='project-details-holder'>
                        <div>
                            <span>Уровни доступа</span>
                            <p>{{ $project->levels->count() }}</p>
                        </div>
                    </a>
                    <a href='/select/{{ $project->domain }}/users' class='project-details-holder'>
                        <div>
                            <span>Пользователи</span>
                            <p>{{ $project->susers->count() }}</p>
                        </div>
                    </a>
                    <div style='clear: both'></div>
                </div>
            </div>
            @endforeach
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-project.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет проектов</div>
                <div class="add-project-text">Создайте проект и добавьте в него<br> ваши материалы и участников</div>
                <div class="add-project-button">
                    <a href="/projects/add" class="green-button">Создать новый проект</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
@endsection