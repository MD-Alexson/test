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
                <p class="help-block">Ваш аккаунт деактивирован</p>
                <hr>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <?php echo html_entity_decode($project->deactivated_html); ?>
                    </div>
                </div>
                @include('frontend_old.inc.auth_footer')
            </div>
        </div>
    </div>
</body>
@endsection