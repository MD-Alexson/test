@extends('shared.app')
@section('content')
<div id="wrapper">
    <header class="header">
        <div class="header-min header-black">
            <div class="holder">
                <div class="logo"><a href="/"><img src="{{ asset('assets/images/logo4.png') }}" alt=""></a></div>
                <nav class="header-nav">
                    <a href="#ability" class="scroll">Возможности</a>
                    <span class="active">Тарифы</span>
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
    <section class="tariff">
        <div class="holder">
            <h2>Тарифы</h2>
            <div class="title-min">Выберите подходящий вам пакет</div>
            <div class="tariff-tabs">
                <ul class="tabs">
                    <li class="tariff-tabs-left"><a href="#month">Ежемесячный</a></li><!--
                    --><li class="tariff-tabs-right"><a href="#year">Годовой –35%</a></li>
                </ul>
            </div>
            <div class="tariff-holder">
                <div id="month" class="tariff-block">
                    <div class="tariff-col">
                        <div class="tariff-col-name">Стартовый</div>
                        <div class="tariff-col-price">$29/месяц</div>
                        <div class="tariff-col-text">при оплате за год <br>вы экономите $120</div>
                        <ul class="tariff-col-list">
                            <li>3 проекта</li>
                            <li>1 000 пользователей</li>
                            <li>1 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li class="disabled">Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=1&term=1" class="button">Попробовать 14 дней</a>
                        </div>
                    </div>
                    <div class="tariff-col tariff-col-mid">
                        <div class="tariff-col-name">PRO</div>
                        <div class="tariff-col-price">$149/месяц</div>
                        <div class="tariff-col-text">при оплате за год <br>вы экономите $600</div>
                        <ul class="tariff-col-list">
                            <li>25 проектов</li>
                            <li>100 000+ пользователей</li>
                            <li>5 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li>Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=3&term=1" class="button">Выбрать</a>
                        </div>
                    </div>
                    <div class="tariff-col">
                        <div class="tariff-col-name">Бизнес</div>
                        <div class="tariff-col-price">$75/месяц</div>
                        <div class="tariff-col-text">при оплате за год <br>вы экономите $312</div>
                        <ul class="tariff-col-list">
                            <li>10 проектов</li>
                            <li>5 000 пользователей</li>
                            <li>2 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li class="disabled">Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=2&term=1" class="button">Выбрать</a>
                        </div>
                    </div>
                </div>
                <div id="year" class="tariff-block">
                    <div class="tariff-col">
                        <div class="tariff-col-name">Стартовый</div>
                        <div class="tariff-col-price">$19/месяц</div>
                        <div class="tariff-col-text">$228 в год<br>вы экономите $120</div>
                        <ul class="tariff-col-list">
                            <li>3 проекта</li>
                            <li>1 000 пользователей</li>
                            <li>1 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li class="disabled">Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=1&term=12" class="button">Выбрать</a>
                        </div>
                    </div>
                    <div class="tariff-col tariff-col-mid">
                        <div class="tariff-col-name">PRO</div>
                        <div class="tariff-col-price">$99/месяц</div>
                        <div class="tariff-col-text">$1188 в год<br>вы экономите $600</div>
                        <ul class="tariff-col-list">
                            <li>25 проектов</li>
                            <li>100 000+ пользователей</li>
                            <li>5 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li>Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=3&term=12" class="button">Выбрать</a>
                        </div>
                    </div>
                    <div class="tariff-col">
                        <div class="tariff-col-name">Бизнес</div>
                        <div class="tariff-col-price">$49/месяц</div>
                        <div class="tariff-col-text">$588 в год<br>вы экономите $312</div>
                        <ul class="tariff-col-list">
                            <li>10 проектов</li>
                            <li>5 000 пользователей</li>
                            <li>2 ГБ на диске <br>для файлов и видео</li>
                            <li>Загрузка и защита видео</li>
                            <li class="disabled">Дополнительные <br>возможности</li>
                        </ul>
                        <div class="tariff-col-button">
                            <a href="/payment/?plan=2&term=12" class="button">Выбрать</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ability" id="ability">
        <div class="holder">
            <h2>Возможности каждого тарифа</h2>
            <div class="title-min">Узнайте больше о возможностях тарифных планов</div>
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
    @include('shared.inc.footer')
</div>
@include('shared.inc.modals')
@endsection