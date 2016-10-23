@if(Session::has('popup_info'))
<div class="modal fade" tabindex="-1" role="dialog" id='popup_info'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ Session::get('popup_info')[0] }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ Session::get('popup_info')[1] }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(document).ready(function () {
        $('#popup_info').modal();
    });
</script>
@endif
@if(count($errors) > 0)
<div class="modal fade" tabindex="-1" role="dialog" id='popup_info'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                @if(count($errors) === 1)
                <h4 class="modal-title">Ошибка:</h4>
                @else
                <h4 class="modal-title">Ошибки:</h4>
                @endif
            </div>
            <div class="modal-body">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(document).ready(function () {
        $('#popup_info').modal();
    });
</script>
@endif