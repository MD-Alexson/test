@extends('shared.app')
@section('content')
<div id="wrapper">
    <header class="header">
        <div class="header-big">
            <div class="holder">
                <div class="header-big-top">
                    <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo.png') }}" alt=""></a></div>
                    <nav class="header-nav">
                        <a href="#ability" class="scroll">Возможности</a>
                        <a href="/plans">Тарифы</a>
                    </nav>
                    <div class="header-right">
                        @if(Auth::guard('backend')->check())
                        <a href="/projects" class="header-enter">Войти</a>
                        @else
                        <a href="#login" class="header-enter modal">Войти</a>
                        @endif
                        <a href="#register" class="header-reg modal">Регистрация<span>14 дней бесплатно!</span></a>
                    </div>
                </div>
                <div class="header-big-main">
                    <h1 class="header-big-title">Онлайн-платформа №1 для <br>автоматизации инфобизнеса</h1>
                    <div class="header-big-subtitle">Без знания кода, плагинов и <span style="font-weight: 700; color: #fee686">без помощи программистов</span></div>
                    <div class="header-big-text">Узнайте за 5 минут, как просто и безопасно хранить,<br>защищать и продавать продукты вашим клиентам</div>
                    <div class="header-big-button">
                        <a href="javascript:void(0);">Смотреть видео</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-min header-fix">
            <div class="holder">
                <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo_header.png') }}" alt=""></a></div>
                <nav class="header-nav">
                    <a href="#ability" class="scroll">Возможности</a>
                    <a href="/plans">Тарифы</a>
                </nav>
                <div class="header-right">
                    @if(Auth::guard('backend')->check())
                    <a href="/projects" class="header-enter">Войти</a>
                    @else
                    <a href="#login" class="header-enter modal">Войти</a>
                    @endif
                    <a href="#register" class="header-reg modal">Регистрация</a>
                </div>
            </div>
        </div>
    </header>
    <section class="main-slider">
        <div class="holder">
            <h2>Храните, защищайте<br>
                и продавайте ваши продукты!</h2>
            <div class="title-min">Всё для ведения инфобизнеса в одном окне</div>
            <div class="main-slider-holder">
                <div class="carousel">
                    <div class="slides">
                        <div class="slideItem">
                            <a href="javascript: void(0);"><img src="{{ asset('assets/images/media/slide1.jpg') }}"/></a>
                        </div>
                        <div class="slideItem">
                            <a href="javascript: void(0);"><img src="{{ asset('assets/images/media/slide2.jpg') }}"/></a>
                        </div>
                        <div class="slideItem">
                            <a href="javascript: void(0);"><img src="{{ asset('assets/images/media/slide3.jpg') }}"/></a>
                        </div>
                        <div class="slideItem">
                            <a href="javascript: void(0);"><img src="{{ asset('assets/images/media/slide4.jpg') }}"/></a>
                        </div>
                        <div class="slideItem">
                            <a href="javascript: void(0);"><img src="{{ asset('assets/images/media/slide5.jpg') }}"/></a>
                        </div>
                    </div>
                </div>
                <div class="description">
                    <div>
                        Добавьте ваш проект: коучинг, семинар, серию мастер-классов и другие
                    </div>
                    <div>
                        Управляйте всеми вашими проектами в одном окне
                    </div>
                    <div>
                        Добавляйте, импортируйте и экспотируйте ваших клиентов из CSV таблицы
                    </div>
                    <div>
                        Создавайте ваш контент
                    </div>
                    <div>
                        Управляйте публикациями, вебинарами, пользователями, заданиями, комментариями
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="info">
        <div class="holder">
            <div class="info-block">
                <div class="info-block-img"><img src="{{ asset('assets/images/media/info1.jpg') }}" alt=""></div>
                <div class="info-block-content">
                    <div class="info-block-title">Подключайте популярные системы <br>приема платежей</div>
                    <div class="info-block-text">Интегрируйте в ваш кабинет агрегаторы платежных систем justclick или e-autopay при помощи нескольких кликов</div>
                    <div class="info-block-button">
                        <a href="#info1" class="button modal">Подключить платежи</a>
                    </div>
                </div>
            </div>
            <div class="info-block info-block-right">
                <div class="info-block-img"><img src="{{ asset('assets/images/media/info2.jpg') }}" alt=""></div>
                <div class="info-block-content">
                    <div class="info-block-title">Проводите вебинары</div>
                    <div class="info-block-text">Настройте трансляцию вебинара с помощью вставки специального кода, запланируйте дату и время проведения. Укажите действие по таймеру с момента начала вебинара, например, появление кнопки или ссылки.</div>
                    <div class="info-block-button">
                        <a href="#info2" class="button modal">Запланировать вебинар</a>
                    </div>
                </div>
            </div>
            <div class="info-block">
                <div class="info-block-img"><img src="{{ asset('assets/images/media/info3.jpg') }}" alt=""></div>
                <div class="info-block-content">
                    <div class="info-block-title">Добавляйте вложения <br>и домашние задания</div>
                    <div class="info-block-text">Разместите документы PDF, DOC, XLS, MP4, MP3 и другие форматы для возможности скачивания их вашим клиентам. Добавляйте задания к материалам и проверяйте их выполнение.</div>
                    <div class="info-block-button">
                        <a href="#info3" class="button modal">Создать продукт</a>
                    </div>
                </div>
            </div>
            <div class="info-block info-block-right">
                <div class="info-block-img"><img src="{{ asset('assets/images/media/info4.jpg') }}" alt=""></div>
                <div class="info-block-content">
                    <div class="info-block-title">Продавайте продукты <br>с регулярными платежами</div>
                    <div class="info-block-text">Продавайте продукты с регулярной оплатой один раз в неделю, месяц, год или другой удобный период. Настройте оповещения клиентам по окончании оплаченного периода.</div>
                    <div class="info-block-button">
                        <a href="#info4" class="button modal">Создать membership-продукт</a>
                    </div>
                </div>
            </div>
            <div class="info-block">
                <div class="info-block-img"><img src="{{ asset('assets/images/media/info5.jpg') }}" alt=""></div>
                <div class="info-block-content">
                    <div class="info-block-title">Управляйте пользователями <br>и уровнями доступа</div>
                    <div class="info-block-text">Добавьте, импортируйте или экспортируйте пользователей из CSV таблицы. Создавайте права доступа для отдельных пользователей с различными возможностями.</div>
                    <div class="info-block-button">
                        <a href="#info5" class="button modal">Добавить пользователей</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ability" id="ability">
        <div class="holder">
            <h2>Основные возможности</h2>
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
    <section class="bottom-block">
        <div class="holder">
            <div class="bottom-block-left">
                <div class="bottom-block-title">Начните создавать и продавать
                    обучающие материалы вашим клиентам
                    без помощи технических специалистов</div>
                <div class="bottom-button">
                    <a href="#register" class="button modal">Создать кабинет</a>
                    <div class="bottom-button-text">14 дней бесплатно!</div>
                </div>
            </div>
        </div>
    </section>
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
<div id="popup3" class="popup-video"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".header-big-button a").on('click', function(e){
            e.preventDefault();
            var video_id = "mHH1oyWzJZM";
            $.fancybox("#popup3", {
                afterShow: function(){
                    $("#popup3").html('<iframe src="https://www.youtube.com/embed/'+video_id+'?autoplay=1&rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
                },
                afterClose: function(){
                    $("#popup3").html("");
                }
            });
        });
    });
</script>
@endsection