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
                <h3><a href="/">{{ $project->name }}</a></h3>
                <p class="help-block">Создание пароля</p>
                <hr>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p>Ссылка на создание нового пароля была отправлена на ваш email!</p>
                        <p>Если email не в папка "Входяшие", проверьте папку "СПАМ"</p>
                    </div>
                </div>
                @include('frontend.inc.auth_footer')
            </div>
        </div>
    </div>
</body>
@endsection