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
                        <h1>{{ $project->name }}</h1>
                        @if(!empty($project->header_text))
                        <hr class="small">
                        <h2>{{ $project->header_text }}</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container">
        <div class="row list-content">
            @if($project->sidebar)
            <div class="col-xs-8">
                @if(!empty($project->dashboard_html))
                <?php echo html_entity_decode($project->dashboard_html); ?>
                <br/>
                @endif
                @include('frontend.inc.list_cats', ['entities' => $cats])
            </div>
            <div class="col-xs-4" id="sidebar">
                <?php echo html_entity_decode($project->sidebar_html); ?>
            </div>
            @else
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                @if(!empty($project->dashboard_html))
                <?php echo html_entity_decode($project->dashboard_html); ?>
                <br/>
                @endif
                @include('frontend.inc.list_cats', ['entities' => $cats])
            </div>
            @endif
        </div>
    </div>
    @include('frontend.inc.footer')
</body>
@endsection