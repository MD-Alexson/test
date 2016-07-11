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
                        <hr class="small">
                        <h2>Настройки аккаунта</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
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