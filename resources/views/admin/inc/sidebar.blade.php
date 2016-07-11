<aside class="cabinet-side">
    <div class="side-logo">
        <a href="/users"><img src="{{ asset('assets/images/logo_admin.png') }}" alt=""></a>
    </div>
    <ul class="side-nav">        
        <li class="side-nav-item nav-item4">
            <a href="/users">Пользователи</a>
        </li>
    </ul>
</aside>
<script type="text/javascript">
    $(document).ready(function(){
        var path = window.location.pathname.split("/");
        $("ul.side-nav a").each(function(){
            var href = $(this).attr('href').slice(1);
            if(path.indexOf(href) !== -1 && href !== ""){
                $(this).addClass('active');
                $(this).closest(".side-nav-item").children('a.side-parent').addClass('active');
                $(this).closest("ul.side-subnav").show();
            }
        });
    });
</script>