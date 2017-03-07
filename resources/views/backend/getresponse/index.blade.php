@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-title">Настройки GetResponse</div>
            <div class="add-material">
                <div class="material-note">
                    Тут вы можете настроить интеграцию с сервисом рассылок GetResponse.<br/>
                    Все новые пользователи будут попадать в назначенные кампании GetResponse,<br/>
                    перемещаться между ними при изменении уровня доступа и удаляться из них,<br/>
                    если пользователь удален из проекта.
                </div>
                <form action="{{ action('Backend\GetResponseController@settings') }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Api ключ</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите API ключ" name="api_key" required="required" maxlength="32" value="{{ $project->gr_api_key }}">
                        </div>
                        <div class="add-mat-title">Если у вас GetResponse 360</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Домен</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input input-long" placeholder="Введите ваш Enterprise домен" name="domain" maxlength="128" value="{{ $project->gr_domain }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Api URL</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="api_url">
                                    @if($project->gr_api_url === 'pl')
                                    <option value="pl" id="pl" selected="selected">.pl</option>
                                    <option value="com" id="com">.com</option>
                                    @else
                                    <option value="pl" id="pl">.pl</option>
                                    <option value="com" id="com" selected="selected">.com</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-right-holder">
                            <button class="green-button float-left" type="submit">Сохранить</button>
                            <a class="white-button float-left gr_test fancybox" href="#popup_test_response">Протестировать</a>
                        </div>
                    </fieldset>
                </form>
            </div>
            @if(isset($getresponse['account']))
            <div class='material-block'>
                <div class="add-mat-title">Настройки кампаний</div>
                <form action="{{ action('Backend\GetResponseController@campaigns') }}" method="post" onsubmit="$.fancybox('#popup_sync_response');">
                    <div class="material-table">
                        <table>
                            <col width='33%'>
                            <col width='34%'>
                            <col width='33%'>
                            <!--<col width='20%'>-->
                            <tr style='font-weight: 700'>
                                <td>Уровень доступа</td>
                                <td>Кол-во пользователей</td>
                                <td>Кампания в GetResponse</td>
                                <!--<td class="table-icons" style="text-align: center">Действия</td>-->
                            </tr>
                            @foreach($project->levels as $level)
                            <tr class="row">
                                <td>{{ $level->name }}</td>
                                <td><a href="/users/by_level/{{ $level->id }}">{{ $level->susers->count() }}</a></td>
                                <td>
                                    <select class="styled" name="camp_id[{{ $level->id }}]">
                                        <option value=''>—</option>
                                        @foreach($getresponse['campaigns'] as $camp)
                                        @if($camp->campaignId === $level->gr_campaign)
                                        <option value='{{ $camp->campaignId }}' selected="selected">{{ $camp->name }}</option>
                                        @else
                                        <option value='{{ $camp->campaignId }}'>{{ $camp->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </td>
                                <!--
                                <td style="text-align: center">
                                    <div class="cab-icons">
                                        <a href="#popup_send_mail" class="fancybox" onclick="$('#popup_send_mail input[name=level_id]').val('{{ $level->id }}')"><img src='{{ asset("assets/images/mail.png") }}'></a>
                                    </div>
                                </td>
                                -->
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <button class="green-button float-left" type="submit">Сохранить</button>
                    <!--<a class="white-button float-left gr_test fancybox" href="#popup_test_response">Создать кампанию</a>-->
                    <br/><br/><br/>
                </form>
            </div>
            @else
            <div class='material-block'>
                <div class="material-note">
                    <strong>ВНИМАНИЕ!</strong> Сохраненные настройки не корректны!
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_test_response" class="popup-min">
    <div class="popup-min-top">
        <div class="popup-min-title" style='text-align: center!important'>GetResponse: проверка соединения</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">...Тестируем соединение...</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<div id="popup_sync_response" class="popup-min text-center">
    <div class="popup-min-top">
        <div class="popup-min-title" style='text-align: center!important'>GetResponse: синхронизация</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">Ожидайте, идет синхронизация с GetResponse<br/>Это может занять время</div>
    </div>
    <div class="popup-min-bottom">
        <button class="green-button close">Закрыть</button>
    </div>
</div>
<div id="popup_send_mail" class="popup-min text-center">
    <div class="popup-min-top">
        <div class="popup-min-title" style='text-align: center!important'>GetResponse: Отправка сообщения</div>
    </div>
    <div class="popup-min-content">
        <form action="{{ action('Backend\GetResponseController@sendByLevel') }}" method="post" onsubmit="$.fancybox.close(); $.fancybox('#popup_sync_response');" name="sendmail" id="sendmail">
            <input type="text" class="input" placeholder="Введите тему" name="subject" required="required" maxlength="128">
            <textarea cols="30" rows="10" class="textarea" placeholder="Введите ваше сообщение" name="text" required="required"></textarea>
            <input type="hidden" name="level_id" value="">
        </form>
    </div>
    <div class="popup-min-bottom">
        <button class="white-button close">Закрыть</button>
        <button class="green-button" type="submit" form="sendmail">Отправить</button>
    </div>
</div>
<style>
    .ui-selectmenu-button {
        width: 250px!important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("a.gr_test").on("click", function () {
            $('#popup_test_response .popup-min-text').text("...Тестируем соединение...");
            $('#popup_test_response .popup-min-text').css({'color': '#808080'});

            var form = $(this).closest("form");
            var api_key = form.find("input[name=api_key]").val();
            var domain = form.find("input[name=domain]").val();
            var api_url = form.find("select[name=api_url]").val();

            $.ajax({
                type: 'POST',
                url: '/getresponse/test',
                data: 'api_key=' + api_key + '&domain=' + domain + '&api_url=' + api_url,
                success: function (data) {
                    var response = parseInt(data);
                    if (response) {
                        $('#popup_test_response .popup-min-text').html("Соединение успешно!<br/>Можно сохранять настройки");
                        $('#popup_test_response .popup-min-text').css({'color': '#0a0'});
                    } else {
                        $('#popup_test_response .popup-min-text').html("Не удалось подключиться!<br/>   Перепроверьте настройки");
                        $('#popup_test_response .popup-min-text').css({'color': '#a00'});
                    }
                }
            });
        });
    });
</script>
@endsection