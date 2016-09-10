@extends('frontend_old.app')
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
                <h3><a href="/">{{ $project->name }}</a></h3>
                <p class="help-block">Создание пароля</p>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <p>Здравствуйте, {{ $passdata->suser->name }}!<br/>Осталось только ввести новый пароль</p>
                        <form action="{{ action('Frontend\AccountController@passwordChange', ['domain' => $project->domain, 'key' => $passdata->key]) }}" method="post">
                            {{ csrf_field() }}
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Пароль</label>
                                    <input type="password" name="password" class="form-control" placeholder="Введите пароль" required="required" minlength="8" maxlength="20">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Пароль еще раз</label>
                                    <input type="password" name="password_check" class="form-control" placeholder="Введите пароль еще раз" required="required" minlength="8" maxlength="20">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            
                            <br>
                            <button type="submit" class="btn btn-default btn-success btn-block">Создать пароль</button>
                        </form>
                    </div>
                </div>
                @include('frontend_old.inc.auth_footer')
            </div>
        </div>
    </div>
</body>
@endsection