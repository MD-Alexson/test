@extends('shared.app')
@section('content')
<div id="wrapper">
    <header class="header header-partners">
        <div class="header-big">
            <div id="header_blue"></div>
            <div class="holder">
                <div class="header-big-top">
                    <div class="logo"><a href="{{ config('app.url') }}"><img src="{{ asset('assets/images/logo.png') }}" alt=""></a></div>
                    <nav class="header-nav">
                        <a href="{{ config('app.url') }}">О сервисе</a>
                        <a href="{{ config('app.url') }}/plans">Тарифы</a>
                    </nav>
                    <div class="header-right">
                        <a href="https://abckab.e-autopay.com/affreg/" class="header-reg" onclick="yaCounter37334890.reachGoal('partner-reg');">Регистрация</a>
                    </div>
                </div>
                <div class="header-big-main">
                    <h1>Партнерская программа ABC Кабинет<br/><strong>Зарабатывайте до $356 с каждой оплаты. Навсегда</strong></h1>
                    <p>Рекламируйте ABC Кабинет для проведения и автоматизации онлайн-обучения<br/>среди инфобизнесменов, авторов, тренинговых компаний</p>
                    <a href="#partners_video" class="fancybox-video">Смотреть видео</a>
                </div>
            </div>
        </div>
        <div class="header-min header-fix">
            <div class="holder">
                <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo_header.png') }}" alt=""></a></div>
                <nav class="header-nav">
                    <a href="{{ config('app.url') }}">О сервисе</a>
                    <a href="{{ config('app.url') }}/plans">Тарифы</a>
                </nav>
                <div class="header-right">
                    <a href="https://abckab.e-autopay.com/affreg/" class="header-reg" onclick="yaCounter37334890.reachGoal('partner-reg');">Регистрация</a>
                </div>
            </div>
        </div>
    </header>
    <section class="partners_video" id="video">
        <h3><span style="font-family: 'GothaProBol'">Посмотрите видео</span> о преимуществах<br/>нашей партнерской программы</h3>
        <div data-id="8leorFMy0rg" style="background-image: url({{ asset('assets/images/media/dima.jpg') }})">
            <a href="#partners_video" class="fancybox-video">
                <img src="{{ asset('assets/images/video_play.png') }}">
            </a>
        </div>
    </section>
    <section class="ability" id="ability">
        <div class="holder">
            <h2>Основные возможности ABC Кабинет</h2>
            <div class="title-min">Узнайте больше о возможностях нашей системы</div>
            <div class="ability-holder">
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/1.png') }}" alt=""></div>
                    <div class="ability-item-text">Хранение всех <br>продуктов в <br>одном кабинете</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/2.png') }}" alt=""></div>
                    <div class="ability-item-text">Подключение <br>систем приема <br>платежей</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/3.png') }}" alt=""></div>
                    <div class="ability-item-text">Планирование <br>и трансляция <br>вебинаров</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/4.png') }}" alt=""></div>
                    <div class="ability-item-text">Создание и <br>проверка заданий <br>пользователей</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/5.png') }}" alt=""></div>
                    <div class="ability-item-text">Создание <br>продуктов с платным <br>доступом</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/6.png') }}" alt=""></div>
                    <div class="ability-item-text">Импорт и экспорт <br>пользователей <br>из .CSV</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/7.png') }}" alt=""></div>
                    <div class="ability-item-text">Управление <br>уровнями доступа <br>пользователей</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/8.png') }}" alt=""></div>
                    <div class="ability-item-text">Сегментация <br>продуктов <br>по категориям</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/9.png') }}" alt=""></div>
                    <div class="ability-item-text">Защита продуктов <br>от копирования <br>и пиратства</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/10.png') }}" alt=""></div>
                    <div class="ability-item-text">Адаптивность страниц <br>для ПК, планшетов <br>и смартфонов</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/11.png') }}" alt=""></div>
                    <div class="ability-item-text">Настройка дизайна <br>страниц продуктов</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/12.png') }}" alt=""></div>
                    <div class="ability-item-text">Комментарии <br>к публикациям</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/13.png') }}" alt=""></div>
                    <div class="ability-item-text">Интеграция с <br>соц-сетями <br>и блогом</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/14.png') }}" alt=""></div>
                    <div class="ability-item-text">Неограниченное <br>количество <br>публикаций</div>
                </div>
                <div class="ability-item">
                    <div class="ability-item-icon"><img src="{{ asset('assets/images/media/icons/15.png') }}" alt=""></div>
                    <div class="ability-item-text">До 5 Гб на диске<br> для файлов <br>и видео</div>
                </div>
            </div>
        </div>
    </section>
    <section id="partners_terms">
        <div class='holder' style="width: 800px;">
            <h2>Правила партнерской программы</h2>
            <p>1. Партнер привлекает посетителей на сайт <a href="https://abckabinet.ru/">https://abckabinet.ru/</a> по своей специальной реферальной ссылке, получить которую можно в разделе «Мои ссылки», доступном на сайте после регистрации в партнерской программе.<br/><br/>
            2. Запрещено использовать спам и иные нелегальные способы рекламы ваших партнёрских ссылок, иначе ваш аккаунт будет моментально заблокирован без выплаты комиссионных.<br/><br/>
            3. Запрещено пытаться оплатить сервис через свои партнерские ссылки.<br/><br/>
            4. Комиссия начисляется по последнему партнеру. Это значит, что вознаграждение от стоимости оплаченного заказа получает тот партнер, по чьей реферальной ссылке клиент зашел на наш сайт в последний раз перед регистрацией.<br/><br/>
            5. Контекстная реклама Яндекс.Директ и Google Adwords запрещена.<br/><br/>
            6. Cookies – на неограниченный срок (если пользователь их сам не удалит).<br/><br/>
            7. Выплата вознаграждений в течение 10 дней после поступления на Ваш Вебмани, Яндекс.Деньги, RBKMoney.<br/><br/>
            8. Администрация оставляет за собой право вносить изменения в существующие правила партнерской программы в любое время, когда это будет необходимо.</p>
        </div>
    </section>
    <section id='partners_table'>
        <div class='holder'>
            <h2>Размер комиссионных<br/><span style='font-family: "GothaProBol";'>30% партнеру с каждой оплаты его клиента</span></h2>
            <div class='table-holder'>
                <table>
                    <col width='34%'>
                    <col width='33%'>
                    <col width='33%'>
                    <tr>
                        <td>Стоимость пакета</td>
                        <td>Ваша комиссия %</td>
                        <td>Ваша комиссия $</td>
                    </tr>
                    <tr>
                        <td>14 дней бесплатно</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>Стартовый $29 - 1 мес</td>
                        <td>30%</td>
                        <td>$8,7</td>
                    </tr>
                    <tr>
                        <td>Стартовый $78 - 3 мес</td>
                        <td>30%</td>
                        <td>$23,4</td>
                    </tr>
                    <tr>
                        <td>Стартовый $132 - 6 мес</td>
                        <td>30%</td>
                        <td>$39,6</td>
                    </tr>
                    <tr>
                        <td>Стартовый $228 - 12 мес</td>
                        <td>30%</td>
                        <td>$68,4</td>
                    </tr>
                    <tr>
                        <td>Бизнес $75 - 1 мес</td>
                        <td>30%</td>
                        <td>$22,5</td>
                    </tr>
                    <tr>
                        <td>Бизнес $201 - 3 мес</td>
                        <td>30%</td>
                        <td>$60,3</td>
                    </tr>
                    <tr>
                        <td>Бизнес $348 - 6 мес</td>
                        <td>30%</td>
                        <td>$104,4</td>
                    </tr>
                    <tr>
                        <td>Бизнес $588 - 12 мес</td>
                        <td>30%</td>
                        <td>$176,4</td>
                    </tr>
                    <tr>
                        <td>PRO $149 - 1 мес</td>
                        <td>30%</td>
                        <td>$44,7</td>
                    </tr>
                    <tr>
                        <td>PRO $399 - 3 мес</td>
                        <td>30%</td>
                        <td>$119,7</td>
                    </tr>
                    <tr>
                        <td>PRO $696 - 6 мес</td>
                        <td>30%</td>
                        <td>$208,8</td>
                    </tr>
                    <tr>
                        <td>PRO $1188 - 12 мес</td>
                        <td>30%</td>
                        <td>$356,4</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <section id="partner_register">
        <h3>Регистрируйтесь в партнерской программе<br/>и начните зарабатывать!</h3>
        <div id="partner_form">
            <div class="pay-form">
                <a onclick="yaCounter37334890.reachGoal('partner-reg'); return true;" href="https://abckab.e-autopay.com/affreg/" class="green-button">Регистрация</a>
                <p>Регистрируясь, вы соглашаетесь с правилами <a href="#partners_terms" class="scroll">партнерской программы</a></p>
            </div>
        </div>
    </section>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
<div id="partners_video" class="popup-video"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.fancybox-video').fancybox({
            afterShow: function(){
                $("#partners_video").html('<iframe src="https://www.youtube.com/embed/otm-b73qHJU?autoplay=1&rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
            },
            afterClose: function(){
                $("#partners_video").html("");
            }
        });
        $(".table-holder table tr:even").css({'background':'#fff'});
    });
</script>
<style>
    footer {
        background: #f7f7f7!important;
    }
    
    #partners_terms {
        background: #fff;
        padding: 103px 0px 94px 0px;
    }
    
    #partners_terms h2 {
        font-size: 28px;
        line-height: 28px;
        color: #353535;
        font-weight: 400;
        padding: 0px;
        margin: 0px 0px 66px 0px;
        letter-spacing: 0.02em;
    }
    
    #partners_terms p {
        font-size: 18px;
        line-height: 28px;
        color: #444444;
    }
    
    #partners_table {
        padding: 97px 0px 50px 0px;
    }
    
    #partners_table h2 {
        font-size: 28px;
        line-height: 34px;
        letter-spacing: 0.02em;
        color: #353535;
        font-weight: 400;
        padding: 0px;
        margin: 0px 0px 67px 0px;
    }
    
    #partners_table div.table-holder {
        width: 794px;
        margin: 0 auto;
    }
    
    #partners_table div.table-holder table {
        width: 100%;
        text-align: center;
    }
    
    #partners_table div.table-holder table tr td:first-child {
        text-align: left;
        padding-left: 33px;
    }
    
    #partners_table div.table-holder table tr td {
        padding-top: 25px;
        padding-bottom: 25px;
        font-size: 18px;
        line-height: 28px;
        color: #444444;
    }
    
    #partners_table div.table-holder table tr:first-child td {
        font-family: "GothaProBol";
    }
    
    #partner_form .pay-form {
        padding-bottom: 10px;
        text-align: center;
    }
    
    #partner_form .pay-form p {
        font-size: 12px;
        color: #444444;
        line-height: normal;
        margin: 10px 0px 0px 0px;
        padding: 0px;
    }
    
    #partner_form .pay-form p a {
        margin: 0px;
        padding: 0px;
        line-height: normal;
        color: #419aff;
        text-decoration: none;
    }
    
    #partner_form .pay-form p a:hover {
        text-decoration: underline;
    }
    
    #partners_terms a {
        color: #419aff;
        text-decoration: none;
    }
    
    #partners_terms a:hover {
        text-decoration: underline;
    }
</style>
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=X77VVyt/KDCV8ZUko7f5iPkYkwmUJLEkEN9kMFg0L1Ahaoyds2NCeYm3BNjIFkcGHo0Zjl/8kX4xHWIS*Or9B2/tVjDlZgJyeHqRzJmxCuY6swYV2RkrEpsBwt3Hv9*jCadR8IH9OIlqxarhooYJlGaFbrPGm9Pud94FOiLUvng-';</script>
@endsection