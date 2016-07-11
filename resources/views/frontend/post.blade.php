@extends('frontend.app')
@section('content')
<body id="list">
    <?php echo html_entity_decode($project->body_start_user_code); ?>
    
    @include('frontend.inc.nav')
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($post->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="site-heading">
                        <h1>{{ $post->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container">
        <div class="row">
            @if($project->sidebar)
            <div class="col-xs-8">
                <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                @if(frontendCheckLevel($post, Session::get('level_id')))
                    @if(Session::get('guard') === 'backend' || frontendCheckHomeworks($post))
                    @include('frontend.inc.content')
                    @else
                    <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
                        @foreach($post->requiredPosts as $req)
                        <strong>-</strong>{{ $req->name }}<br/>
                        @endforeach
                    </p>
                    @endif
                @elseif($post->category->upsale)
                    <?php echo html_entity_decode($post->category->upsale_text); ?>
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной публикации!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            <div class="col-xs-4" id="sidebar">
                <?php echo html_entity_decode($project->sidebar_html); ?>
            </div>
            @else
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                @if(frontendCheckLevel($post, Session::get('level_id')))
                    @if(Session::get('guard') === 'backend' || frontendCheckHomeworks($post))
                    @include('frontend.inc.content')
                    @else
                    <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
                        @foreach($post->requiredPosts as $req)
                        <strong>-</strong>{{ $req->name }}<br/>
                        @endforeach
                    </p>
                    @endif
                @elseif($post->category->upsale)
                    <?php echo html_entity_decode($post->category->upsale_text); ?>
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной публикации!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @include('frontend.inc.footer')
    <script type="text/javascript">
        $(document).ready(function(){ $("nav#menu li[data-id="+{{ $post->category->id }}+"]").addClass('active'); });
    </script>
</body>
@endsection