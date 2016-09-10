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
                        <form action="{{ action('Frontend\AccountController@password', ['domain' => $project->domain]) }}" method="post">
                            {{ csrf_field() }}
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required="required" maxlength="40">
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