@extends('frontend_old.app')
@section('content')
<body id="list">
    <?php echo html_entity_decode($project->body_start_user_code); ?>

    @include('frontend_old.inc.nav')
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
                        <h2>Мои домашние задания</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend_old.inc.menu')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <div class="col-sm-1" style="padding-left: 0px;">
                    <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                </div>
                <div class="col-sm-11 text-right" style="padding-right: 0px;">
                    <a href="/account/comments" class="btn btn-default">Мои комментарии ({{ $user->comments()->count() }})</a>
                    <a href="/account/homeworks" class="btn btn-default active">Мои домашние задания ({{ $user->homeworks()->count() }})</a>
                </div>
                
            </div>
        </div>
    </div>
    @include('frontend_old.inc.footer')
</body>
@endsection