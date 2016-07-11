@if(Session::has('popup_ok'))
<div id="popup_ok" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-big-icon"><img src="{{ asset('assets/images/ok.png') }}" alt=""></div>
        <div class="popup-min-title">{{ Session::get('popup_ok')[0] }}</div>
        <div class="popup-min-text">{{ Session::get('popup_ok')[1] }}</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_ok");
    });
</script>
@endif
@if(Session::has('popup_info'))
<div id="popup_info" class="popup-min">
    <div class="popup-min-content">
        <div class="popup-min-title">{{ Session::get('popup_info')[0] }}</div>
        <div class="popup-min-text">{{ Session::get('popup_info')[1] }}</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_info");
    });
</script>
@endif
@if(count($errors) > 0)
<div id="popup_errors" class="popup-min">
    <div class="popup-min-content">
        @if(count($errors) === 1)
        <div class="popup-min-title">Ошибка:</div>
        @else
        <div class="popup-min-title">Ошибки:</div>
        @endif
        <div class="popup-min-text">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.fancybox("#popup_errors");
    });
</script>
@endif