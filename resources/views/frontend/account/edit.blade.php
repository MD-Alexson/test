@extends('frontend.app')
@section('content')
<body id="list">
    <?php echo html_entity_decode($project->body_start_user_code); ?>

    @include('frontend.inc.nav')
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($project->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="site-heading">
                        <h1>{{ $user->name }}</h1>
                        <div class='divider'></div>
                        <h3>Настройки аккаунта</h3>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <div class="col-sm-12 text-right" style="padding-right: 0px;">
                    <!--<a href="/account/comments" class="btn btn-default">Мои комментарии ({{ $user->comments()->count() }})</a>-->
                    <!--<a href="/account/homeworks" class="btn btn-default">Мои домашние задания ({{ $user->homeworks()->count() }})</a>-->
                </div>
                <form method="post" action="{{ action("Frontend\AccountController@update", ['domain' => $project->domain]) }}">
                    {{ csrf_field() }}
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Имя</label>
                            <input name="name" type="text" class="form-control" placeholder="Ваше имя" required="required" value="{{ $user->name }}" maxlength="32">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Ваш email" required="required" value="{{ $user->email }}" maxlength="32">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Телефон</label>
                            <input name="phone" type="text" class="form-control" placeholder="Ваш телефон" value="{{ $user->phone }}" maxlength="20">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div style="height: 1px; width: 100%; background: #434343; margin: 20px 0px;"></div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Введите новый пароль</label>
                            <input name="password" type="password" class="form-control" placeholder="Изменить пароль (необязательно)" minlength="8" maxlength="20">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Введите новый пароль еще раз</label>
                            <input name="password_check" type="password" class="form-control" placeholder="Новый пароль еще раз" minlength="8" maxlength="20">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <button type="submit" class="btn btn-default btn-success">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('frontend.inc.footer')
</body>
@endsection