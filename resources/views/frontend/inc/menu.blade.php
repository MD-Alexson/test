@if($project->dashboard_type)
<br/>
@else
<div class="container menu">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <nav class="navbar navbar-default" id="menu">
                <div class="container-fluid">
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                            <span class="sr-only">Навигация</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="/" style="font-weight: 700;">Главная</a>
                            </li>
                            @foreach($menu as $cat_id)
                            <?php $cat_name = \App\Category::where('id', $cat_id)->select('name')->first(); ?>
                            <li data-id="{{ $cat_id }}">
                                <a href="/categories/{{ $cat_id }}">{{ $cat_name->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
@endif