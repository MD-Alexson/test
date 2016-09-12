@extends('frontend.app')
@section('content')
<body id="login">
    <?php echo html_entity_decode($project->body_start_user_code); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" id="login_wrap">
                <div>
                    <a href="{{ config('app.url') }}" target="_blank"><img src="{{ asset('assets/images/logo_header.png') }}" style="cursor: pointer"></a>
                </div>
                <br/>
                <h3>{{ $project->name }}</h3>
                <p class="help-block">Вход</p>
                <hr>
                <div class="row">
                    @if(strlen($project->login_html))
                    <div class="col-md-6">
                        <form action="{{ action('Frontend\AccountController@login', ['domain' => $project->domain]) }}" method="post">
                            {{ csrf_field() }}
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required="required" maxlength="40">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Пароль</label>
                                    <input type="password" name="password" class="form-control" placeholder="Пароль" required="required" minlength="8" maxlength="20">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br>
                            @if($project->levels()->where('open', true)->count())
                            <button type="submit" class="btn btn-default btn-success col-xs-6 no-margin">Войти</button>
                            <a href="/register" class="btn btn-default btn-info col-xs-6 no-margin">Регистрация</a>
                            <div class='clearfix'></div>
                            @else
                            <button type="submit" class="btn btn-default btn-success btn-block">Войти</button>
                            @endif
                            <a href="/pass" class="password_link text-right" style="display: block; margin-top: 15px;">Забыли пароль?</a>
                        </form>
                    </div>
                    <div class="col-md-6" id="login_html">
                        <?php printf(html_entity_decode($project->login_html)); ?>
                    </div>
                    @else
                    <div class="col-md-6 col-md-offset-3">
                        <form action="{{ action('Frontend\AccountController@login', ['domain' => $project->domain]) }}" method="post">
                            {{ csrf_field() }}
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required="required" maxlength="40">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Пароль</label>
                                    <input type="password" name="password" class="form-control" placeholder="Пароль" required="required" minlength="8" maxlength="20">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br>
                            @if($project->levels()->where('open', true)->count())
                            <button type="submit" class="btn btn-default btn-success col-xs-6 no-margin">Войти</button>
                            <a href="/register" class="btn btn-default btn-info col-xs-6 no-margin">Регистрация</a>
                            <div class='clearfix'></div>
                            @else
                            <button type="submit" class="btn btn-default btn-success btn-block">Войти</button>
                            @endif
                            <a href="/pass" class="password_link text-right" style="display: block; margin-top: 15px;">Забыли пароль?</a>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('frontend.inc.footer')
</body>
@endsection