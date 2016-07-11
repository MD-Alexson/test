<div class="content-top">
    <div class="content-top-links">
        <div class="content-notification">
            <a href="" class="content-notification-link content-top-show">
                @if(Auth::guard('backend')->user()->notifications()->where('read', false)->count())
                <span id='not_counter'>{{ Auth::guard('backend')->user()->notifications()->where('read', false)->count() }}</span>
                @endif
            </a>
            <ul class="content-top-hidden">
                @if(Auth::guard('backend')->user()->notifications()->count())
                    @foreach(Auth::guard('backend')->user()->notifications as $not)
                    @if($not->read)
                    <li data-id='{{ $not->id }}' class='not read'>
                    @else
                    <li data-id='{{ $not->id }}' class='not'>
                    @endif
                        <div class="content-hidden-title">{{ $not->name }}</div>
                        <div class="content-hidden-text"><?php echo html_entity_decode($not->text); ?></div>
                    </li>
                    @endforeach
                @else
                <li class='default'>
                    <div class="content-hidden-title">Уведомления</div>
                    <div class="content-hidden-text">У вас нет новых уведомлений</div>
                </li>
                @endif
            </ul>
        </div>
    </div>
    @if(Auth::guard('backend')->user()->projects->count())
    <div class="content-top-select">
        <div class="content-top-select-text">Выбран проект:</div>
        <div class="content-top-select-holder">
            <select class="styled" name="select_project">
                @foreach(Auth::guard('backend')->user()->projects()->orderBy('created_at', 'desc')->get() as $project)
                <option value="{{ $project->domain }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
        @if(Session::has('selected_project'))
        <script type="text/javascript">
            $(document).ready(function(){
                var selected_project = "{{ Session::get('selected_project') }}";
                $("select[name=select_project]").val(selected_project);
                $("select[name=select_project]").selectmenu("refresh");
            });
        </script>
        @endif
    @endif
    <div class="content-login">
        <div class="content-login-holder">
            <a href="" class="content-login-link">
                <div class="content-login-img"><img src="{{ asset('assets/images/media/userpic.png') }}" alt=""></div>
                <span class="username">Здравствуйте, <span class="blue">{{ Auth::guard('backend')->user()->name }}</span></span></a>
            <ul class="content-login-hidden">
                <li><a href="/account">Настройки профиля</a></li>
                <li><a href="/account/plans">Тарифы</a></li>
                @if(strlen(Auth::guard('backend')->user()->partner))
                <li><a href="/p/{{ Auth::guard('backend')->user()->partner }}/again">Оплата</a></li>
                @else
                <li><a href="/account/payment">Оплата</a></li>
                @endif
                <li><a href="/account/logout">Выход</a></li>
            </ul>
        </div>
    </div>
</div>