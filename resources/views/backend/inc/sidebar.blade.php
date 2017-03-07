<aside class="cabinet-side">
    <div class="side-logo">
        <a href="/projects"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a>
    </div>
    <ul class="side-nav">
        <!--<li class="side-nav-item nav-item1"><a href="">Мой кабинет</a></li>-->
        <li class="side-nav-item nav-item2"><a href="/projects">Проекты</a></li>
        <li class="side-nav-item nav-item3">
            <a href="" class="side-parent">Материалы</a>
            <ul class="side-subnav">
                <li><a href="/posts">Публикации</a></li>
                <li><a href="/webinars">Вебинары</a></li>
                <li><a href="/categories">Категории</a></li>
                <li><a href="/comments">Комментарии</a></li>
                <li><a href="/homeworks">Задания</a></li>
            </ul>
        </li>
        <li class="side-nav-item nav-item4">
            <a href="" class="side-parent">Пользователи</a>
            <ul class="side-subnav">
                <li><a href="/users">Список пользователей</a></li>
                <li><a href="/levels">Уровни доступа</a></li>
                <li><a href="/users/import">Импорт</a></li>
                <li><a href="/users/export">Экспорт</a></li>
            </ul>
        </li>
        <li class="side-nav-item nav-item5">
            <a href="" class="side-parent">Интеграции</a>
            <ul class="side-subnav">
                <li><a href="/payments">Настройки оплаты</a></li>
                <li><a href="/getresponse">GetResponse</a></li>
                <li><a href="/ipr">Инфопротектор</a></li>
            </ul>
        </li>
    </ul>
    <a href="/account/faq"><img src="{{ asset('assets/images/faq.png') }}" alt="F.A.Q" style='margin-top: 10px;'>Инструкции по работе</a>
    <a href="#popup_message" class="fancybox message"><img src="{{ asset('assets/images/message.png') }}" alt="message">Написать в поддержку</a>
</aside>
<script type="text/javascript">
    $(document).ready(function(){
        var full_path = window.location.pathname.split("/");
        var path = "";
        var hrefs = ['projects', 'posts', 'webinars', 'categories', 'comments', 'homeworks', 'import', 'export', 'levels', 'users', 'payments', 'getresponse', 'ipr'];
        var found = false;
        hrefs.forEach(function(val, index){
            if(!found && full_path.indexOf(val) !== -1){
                path = val;
                found = true;
            }
        });
        $("ul.side-nav a").each(function(){
            var href = $(this).attr('href').slice(1);
            href = href.split('/');
            href = href[href.length-1];
            if(path.indexOf(href) !== -1 && href !== ""){
                $(this).addClass('active');
                $(this).closest(".side-nav-item").children('a.side-parent').addClass('active');
                $(this).closest("ul.side-subnav").show();
            }
        });
    });
</script>