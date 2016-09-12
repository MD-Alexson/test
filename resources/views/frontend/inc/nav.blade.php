<nav class="navbar unique-color navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand waves-effect waves-light"><a href="/">{{ $project->name }}</a> / <a href="/logout"><span class="fa fa-fw fa-sign-out"></span></a></span>
        </div>

        <div class="collapse navbar-collapse" id="top-nav">
            <ul class="nav navbar-nav navbar-right">
                @if(Session::get('guard') === 'backend')
                <li>
                    <a href="{{ config('app.url') }}/account"><span class="fa fa-fw fa-user"></span> {{ Auth::guard(Session::get('guard'))->user()->name }} (Администратор)</a>
                </li>
                <li class="dropdown" id="level">
                    <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-expanded="false"><span class="current_level"></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach($project->levels as $lvl)
                        <li><a href="javascript: select({{ $lvl->id }});" data-id="{{ $lvl->id }}">{{ $lvl->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <script type='text/javascript'>
                    function select(level_id){
                        var request = $.ajax({
                            url: "/select/" + level_id,
                            type: "GET"
                        });
                        request.success(function(){
                            window.location.reload(true);
                        });
                        request.fail(function (jqXHR, textStatus) {
                            alert("Ошибка: " + textStatus);
                        });
                    }
                    $(document).ready(function(){
                        var level_name = $("#level ul a[data-id={{ Session::get('level_id') }}]").text();
                        $("#level .current_level").text(level_name);
                    });
                </script>
                @else
                <li>
                    @if(Auth::guard('frontend')->user()->level->hidden)
                    <a href="/account"><span class="fa fa-fw fa-user"></span> {{ Auth::guard(Session::get('guard'))->user()->name }}</a>
                    @else
                    <a href="/account"><span class="fa fa-fw fa-user"></span> {{ Auth::guard(Session::get('guard'))->user()->name }} ({{ Auth::guard('frontend')->user()->level->name }})</a>
                    @endif
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>