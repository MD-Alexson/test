@extends('frontend_old.app')
@section('content')
<body id="list">
    <?php echo html_entity_decode($project->body_start_user_code); ?>
    
    @include('frontend_old.inc.nav')
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($category->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="site-heading">
                        <h1>{{ $category->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend_old.inc.menu')
    <div class="container">
        <div class="row list-content">
            @if($category->sidebar)
            <div class="col-xs-8">
                <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                @if(frontendCheckLevel($category, Session::get('level_id')))
                    @if(!empty($category->category_html))
                    <?php echo html_entity_decode($category->category_html); ?>
                    <br/>
                    @endif
                    @include('frontend_old.inc.list_posts_by_cat', ['entities' => $posts, 'cat_id' => $category->id])
                @elseif($category->upsale)
                    <?php echo html_entity_decode($category->upsale_text); ?>
                @elseif($category->posts()->where('upsale', true)->count())
                    @if(!empty($category->category_html))
                    <?php echo html_entity_decode($category->category_html); ?>
                    <br/>
                    @endif
                    @include('frontend_old.inc.list_posts_by_cat', ['entities' => $posts, 'cat_id' => $category->id])
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной категории!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            <div class="col-xs-4" id="sidebar">
                @if($category->sidebar_type === 1)
                <?php echo html_entity_decode($category->sidebar_html); ?>
                @else
                <?php echo html_entity_decode($project->sidebar_html); ?>
                @endif
            </div>
            @else
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                <a href="javascript: history.back();" class="btn btn-default btn-circle"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                @if(frontendCheckLevel($category, Session::get('level_id')))
                    @if(!empty($category->category_html))
                    <?php echo html_entity_decode($category->category_html); ?>
                    <br/>
                    @endif
                    @include('frontend_old.inc.list_posts_by_cat', ['entities' => $posts, 'cat_id' => $category->id])
                @elseif($category->upsale)
                    <?php echo html_entity_decode($category->upsale_text); ?>
                @elseif($category->posts()->where('upsale', true)->count())
                    @if(!empty($category->category_html))
                    <?php echo html_entity_decode($category->category_html); ?>
                    <br/>
                    @endif
                    @include('frontend_old.inc.list_posts_by_cat', ['entities' => $posts, 'cat_id' => $category->id])
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной категории!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @include('frontend_old.inc.footer')
    <script type="text/javascript">
        $(document).ready(function(){ $("nav#menu li[data-id="+{{ $category->id }}+"]").addClass('active'); });
    </script>
</body>
@endsection