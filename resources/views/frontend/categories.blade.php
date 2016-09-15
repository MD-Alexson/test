@extends('frontend.app')
@section('content')
    @include('frontend.inc.nav')
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($project->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <h1>{{ $project->name }}</h1>
                    @if(!empty($project->header_text))
                    <div class="divider"></div>
                    <h3>{{ $project->header_text }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </header>
    @include('frontend.inc.menu')
    <div class="container" id="content">
        
        <div class="row">
            @if($project->sidebar)
            <div class="col-md-8 thin">
                @if(!empty($project->dashboard_html))
                <?php echo html_entity_decode($project->dashboard_html); ?>
                <br/>
                @endif
                @include('frontend.inc.lists.cats', ['cats' => $cats])
            </div>
            <div class="col-md-4" id="sidebar">
                <?php echo html_entity_decode($project->sidebar_html); ?>
            </div>
            @else
            <div class="col-md-10 col-md-offset-1 wide">
                @if(!empty($project->dashboard_html))
                <?php echo html_entity_decode($project->dashboard_html); ?>
                <br/>
                @endif
                @include('frontend.inc.lists.cats', ['cats' => $cats])
            </div>
            @endif
        </div>
    </div>
    @include('frontend.inc.footer')
@endsection