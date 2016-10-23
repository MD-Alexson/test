<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand"><a href="/">{{ $project->name }}</a> / <a href="/logout"><span class="fa fa-fw fa-sign-out"></span></a></span>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if(Session::get('guard') === 'backend')
                <li>
                    <a href="{{ config('app.url') }}/account"><span class="fa fa-fw fa-user"></span> {{ Auth::guard(Session::get('guard'))->user()->name }} (Администратор)</a>
                </li>
                <li>
                    <select name='level'>
                        @foreach($project->levels as $lvl)
                        <option value='{{ $lvl->id }}'>{{ $lvl->name }}</option>
                        @endforeach
                    </select>
                </li>
                <script type='text/javascript'>
                    $(document).ready(function(){
                        $("select[name=level]").val({{ Session::get('level_id') }});
                        $("select[name=level]").on('change', function () {
                            var level_id = $(this).val();
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

                        });
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