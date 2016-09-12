@if($project->dashboard_type)
<br/>
@else
<nav class="navbar navbar-default stylish-color" id="menu">
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
                <?php $cat = \App\Category::where('id', $cat_id)->select('name', 'parent')->first(); ?>
                @if($cat->parent === -1)
                <?php $subs = \App\Category::where('parent', $cat_id)->count(); ?>
                @if($subs)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $cat->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li data-id="{{ $cat_id }}"><a href="/categories/{{ $cat_id }}">{{ $cat->name }}</a>
                        <li role="separator" class="divider"></li>
                        @foreach(\App\Category::where('parent', $cat_id)->orderBy('order', 'asc')->select('id', 'name')->get() as $sub)
                        <li data-id="{{ $sub->id }}"><a href="/categories/{{ $sub->id }}">{{ $sub->name }}</a>
                            @endforeach
                    </ul>
                </li>
                @else
                <li data-id="{{ $cat_id }}">
                    <a href="/categories/{{ $cat_id }}">{{ $cat->name }}</a>
                </li>
                @endif
                @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
@endif