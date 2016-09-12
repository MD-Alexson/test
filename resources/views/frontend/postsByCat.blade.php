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
    <div class="container" id="content">
        <div class="row">
            @if($project->sidebar)
            <div class="col-md-8 thin">
                @if(!empty($category->category_html))
                <?php echo html_entity_decode($category->category_html); ?>
                <br/>
                @endif
                @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
            </div>
            <div class="col-md-4" id="sidebar">
                <?php echo html_entity_decode($project->sidebar_html); ?>
            </div>
            @else
            <div class="col-md-10 col-md-offset-1 col-sm-12 wide">
                @if(!empty($category->category_html))
                <?php echo html_entity_decode($category->category_html); ?>
                <br/>
                @endif
                @include('frontend.inc.lists.posts_by_cat', ['posts' => $posts])
            </div>
            @endif
        </div>
    </div>
    @include('frontend.inc.footer')
@endsection