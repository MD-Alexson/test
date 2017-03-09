@extends('frontend.app')
@section('content')
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
    <?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $post->sch2num, $post->sch2type); ?>
    <div class="container" id="content">
        <div class="row">
            @if($post->sidebar)
            <div class="col-md-8 thin" id="left">
                @if(frontendCheckLevel($post, Session::get('level_id')) && ($post->status === 'published' || Auth::guard('backend')->check() || ($post->status === 'scheduled2' && $sch2 <= getTime())))
                    @if(Session::get('guard') === 'backend' || frontendCheckHomeworks($post))
                    @include('frontend.inc.content')
                    @else
                    <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
                        @foreach($post->requiredPosts as $req)
                        <strong>-</strong>{{ $req->name }}<br/>
                        @endforeach
                    </p>
                    @endif
                @elseif($post->upsale)
                    <?php echo html_entity_decode($post->upsale_text); ?>
                @elseif($post->category->upsale)
                    <?php echo html_entity_decode($post->category->upsale_text); ?>
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной публикации!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            <div class="col-md-4" id="sidebar">
                @if($post->sidebar_type === 2)
                <?php echo html_entity_decode($post->sidebar_html); ?>
                @elseif($post->sidebar_type === 1)
                    @if($post->category->sidebar_type === 1)
                    <?php echo html_entity_decode($post->category->sidebar_html); ?>
                    @else
                    <?php echo html_entity_decode($project->sidebar_html); ?>
                    @endif
                @else
                <?php echo html_entity_decode($project->sidebar_html); ?>
                @endif
            </div>
            @else
            <div class="col-md-10 col-md-offset-1 wide" id="left">
                @if(frontendCheckLevel($post, Session::get('level_id')) && ($post->status === 'published' || Auth::guard('backend')->check() || ($post->status === 'scheduled2' && $sch2 <= getTime())))
                    @if(Session::get('guard') === 'backend' || frontendCheckHomeworks($post))
                    @include('frontend.inc.content')
                    @else
                    <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
                        @foreach($post->requiredPosts as $req)
                        <strong>-</strong>{{ $req->name }}<br/>
                        @endforeach
                    </p>
                    @endif
                @elseif($post->upsale)
                    <?php echo html_entity_decode($post->upsale_text); ?>
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
        
        @if(Session::get('guard') === "frontend")
            var user_rnd = "{{ Auth::guard(Session::get('guard'))->user()->rand }}";
        @else
            var user_rnd = "admin62256225";
        @endif
        $("a.dkpdf").each(function(){
            var pdf = $(this).attr('href');
            pdf = "/dkpdf/"+user_rnd+"/"+pdf;
            $(this).attr('href', pdf);
        });
        
        @if($post->id === 10587 || $post->id === 10594)
            $("#post-content ul").addClass('files');
            $("#post-content ul li img").addClass('img-thumbnail');
        @endif
        
        @if($ipr_key)
            var key = "{{ $ipr_key }}";
            console.log(key);
            $(".ipr").each(function(){
                $(this).text(key);
            });
            $("#post-content a").each(function(){
                var href = $(this).attr('href').split("?")[0];
                href = href.split(".");
                if(href.pop() === "ipr"){
                    href.push("ipr?sn="+key);
                    href = href.join(".");
                    $(this).attr('href', href);
                }
            });
        @endif
    </script>
@endsection