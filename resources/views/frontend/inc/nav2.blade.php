<nav class="navbar unique-color navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand waves-effect waves-light">{{ $project->name }}</a>
        </div>

        <div class="collapse navbar-collapse" id="top-nav">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="/login"><span class="fa fa-fw fa-user"></span> Войти</a>
                </li>
            </ul>
        </div>
    </div>
</nav>