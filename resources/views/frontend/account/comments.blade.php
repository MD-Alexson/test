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
                        <h2>Мои комментарии</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <div class="col-sm-1" style="padding-left: 0px;">
                    <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                </div>
                <div class="col-sm-11 text-right" style="padding-right: 0px;">
                    <a href="/account/comments" class="btn btn-default active">Мои комментарии ({{ $user->comments()->count() }})</a>
                    <a href="/account/homeworks" class="btn btn-default">Мои домашние задания ({{ $user->homeworks()->count() }})</a>
                </div>
                <div class="clearfix"></div>
                @foreach($user->comments()->orderBy('created_at', 'desc')->get() as $comment)
                <?php 
                $wait = "";
                if(!$comment->allowed){
                    $wait = " <span style='color: #a00'>(На рассмотрении)</span>";
                }
                ?>
                    <div class='comment'>
                        <h4>[<span class='toLocalTime'>{{ $comment->created_at }}</span>] к записи <a href="/posts/{{ $comment->post->id }}" style="color: #0085a1">{{ $comment->post->name }}</a><?php echo $wait; ?> <a href='/account/comments/{{ $comment->id }}/destroy' class='destroy'>&times;</a></h4>
                        <p><?php echo nl2br($comment->text); ?></p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('frontend.inc.footer')
</body>
@endsection