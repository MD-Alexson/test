<div class="pagination-holder">
    <div class="pagination-left">
    @if(isset($query))
        {{ $entities->appends(['query' => $query])->links() }}
    @else
        {{ $entities->links() }}
    @endif
    </div>
    <div class="pagination-rezult">
        <div class="pagination-rezult-text">Результатов на странице</div>
        <div class="select-block">
            <select class="styled" name='perpage'>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".pagination-holder select[name=perpage]").val("{{ Session::get('perpage') }}").selectmenu("refresh");
        $(".pagination-holder select[name=perpage]").on("selectmenuselect", function(){
            var perpage = $(this).val();
            var request = $.ajax({
                url: "/perpage/" + perpage,
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