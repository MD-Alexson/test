@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <section class="tariff">
                <div class="holder">
                    <h2 style="text-align: center">Тарифы</h2>
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
                                    <a href="{{ $data['link'] }}?plan=1&term=1" class="button">Выбрать</a>
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
                                    <a href="{{ $data['link'] }}?plan=3&term=1" class="button">Выбрать</a>
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
                                    <a href="{{ $data['link'] }}?plan=2&term=1" class="button">Выбрать</a>
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
                                    <a href="{{ $data['link'] }}?plan=1&term=12" class="button">Выбрать</a>
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
                                    <a href="{{ $data['link'] }}?plan=3&term=12" class="button">Выбрать</a>
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
                                    <a href="{{ $data['link'] }}?plan=2&term=12" class="button">Выбрать</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
@endsection