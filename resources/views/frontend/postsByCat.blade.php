@extends('frontend.app')
@section('content')
    @include('frontend.inc.nav')
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($category->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <h1>{{ $category->name }}</h1>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $category->sch2num, $category->sch2type); ?>
    <div class="container" id="content">
        <div class="row">
            @if($project->sidebar)
            <div class="col-md-8 thin" id="left">
                @if(frontendCheckLevel($category, Session::get('level_id')) && ($category->status === "published" || Auth::guard('backend')->check() || ($category->status === "scheduled2" && $sch2 <= getTime())))
                        @if(!empty($category->category_html))
                        <?php echo html_entity_decode($category->category_html); ?>
                        <br/>
                        @endif
                        @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
                @elseif($category->upsale)
                        <?php echo html_entity_decode($category->upsale_text); ?>
                @elseif($category->posts()->where('upsale', true)->count())
                        @if(!empty($category->category_html))
                        <?php echo html_entity_decode($category->category_html); ?>
                        <br/>
                        @endif
                        @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной категории!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            <div class="col-md-4" id="sidebar">
                @if($category->sidebar_type === 1)
                <?php echo html_entity_decode($category->sidebar_html); ?>
                @else
                <?php echo html_entity_decode($project->sidebar_html); ?>
                @endif
            </div>
            @else
            <div class="col-md-10 col-md-offset-1 wide" id="left">
                @if(frontendCheckLevel($category, Session::get('level_id')) && ($category->status === "published" || Auth::guard('backend')->check() || ($category->status === "scheduled2" && $sch2 <= getTime())))
                        @if(!empty($category->category_html))
                        <?php echo html_entity_decode($category->category_html); ?>
                        <br/>
                        @endif
                        @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
                @elseif($category->upsale)
                        <?php echo html_entity_decode($category->upsale_text); ?>
                @elseif($category->posts()->where('upsale', true)->count())
                        @if(!empty($category->category_html))
                        <?php echo html_entity_decode($category->category_html); ?>
                        <br/>
                        @endif
                        @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
                @else
                <p style="color: #cc0000">Вы не имеете доступа к данной категории!</p>
                <a href='/'>На главную</a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @include('frontend.inc.footer')
    <script type="text/javascript">
        $(document).ready(function(){ $("nav#menu li[data-id="+{{ $category->id }}+"]").addClass('active'); });
    </script>
@endsection