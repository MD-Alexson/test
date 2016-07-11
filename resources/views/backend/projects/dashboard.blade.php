@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/projects">К списку всех проектов</a>
            </div>
            <div class="project-new project-dashboard">
                <h1>{{ $project->name }}</h1>
                <div class='project-links'>
                    <a href='{{ getPreviewLink('project', $project->domain) }}' class='left' target="_blank">Предпросмотр</a>
                    <a href='/settings' class='right'>Редактировать</a>
                </div>
                <div class='project-details'>
                    <a href='/posts' class='project-details-holder'>
                        <div>
                            <span>Публикации</span>
                            <p>{{ $project->posts->count() }}</p>
                        </div>
                    </a>
                    <a href='/categories' class='project-details-holder'>
                        <div>
                            <span>Категории</span>
                            <p>{{ $project->categories->count() }}</p>
                        </div>
                    </a>
                    <a href='/levels' class='project-details-holder'>
                        <div>
                            <span>Уровни доступа</span>
                            <p>{{ $project->levels->count() }}</p>
                        </div>
                    </a>
                    <a href='/users' class='project-details-holder'>
                        <div>
                            <span>Пользователи</span>
                            <p>{{ $project->susers->count() }}</p>
                        </div>
                    </a>
                    <a href='/webinars' class='project-details-holder'>
                        <div>
                            <span>Вебинары</span>
                            <p>{{ $project->webinars->count() }}</p>
                        </div>
                    </a>
                    <a href='/comments' class='project-details-holder'>
                        <div>
                            <span>Комментарии</span>
                            <p>{{ $project->comments->count() }}</p>
                        </div>
                    </a>
                    <a href='/homeworks' class='project-details-holder'>
                        <div>
                            <span>Задания</span>
                            <p>{{ $project->homeworks->count() }}</p>
                        </div>
                    </a>
                    <div style='clear: both'></div>
                </div>
            </div>
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
@endsection