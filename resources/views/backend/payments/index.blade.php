@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            @if($payments->count())
            <div class="project-top">
                <div class="project-left">
                    <div class="project-top-title">Все настройки оплат</div>
                    <div class="project-top-num">{{ $payments->count() }}</div>
                </div>
                <div class="project-right">
                    <a href="/payments/add" class="green-button" style="width: auto; padding-left: 15px; padding-right: 15px">Добавить настройки оплаты</a>
                </div>
            </div>
            <div class="material-block">
                <form class="material-controls disabled" action="{{ action("Backend\PaymentsController@batch") }}" method="post">
                    {{ csrf_field() }}
                    <div class="material-control-item">
                        <div class="material-control-text">Действия с выбранными</div>
                        <div class="select-block">
                            <select class="styled" name="action">
                                <option value="delete">Удалить</option>
                            </select>
                        </div>
                    </div>
                    <div class="material-control-item">
                        <input type="hidden" name="ids" value="">
                        <button type="submit" class="blue-button">Применить</button>
                    </div>
                    <div class="material-controls-mask"></div>
                </form>
                <div class="material-table">
                    <table>
                        <col width="4%">
                        <col width="30%">
                        <col width="26%">
                        <col width="30%">
                        <col width="10%">
                        <tr>
                            <td><input name="check_all" type="checkbox" class="check"></td>
                            <td>Платежная система</td>
                            <td>ID Товара в системе</td>
                            <td>Уровень доступа пользователя</td>
                            <td class="table-icons" style="text-align: center">Действия</td>
                        </tr>
                        @foreach($payments as $payment)
                        <tr class="row" data-id="{{ $payment->id }}">
                            <td><input name="check" type="checkbox" class="check"></td>
                            <td>
                                {{ $payment->method }}
                            </td>
                            <td>
                                {{ $payment->item_id }}
                            </td>
                            <td>
                                {{ $payment->level->name }}
                            </td>
                            <td style="text-align: center">
                                <div class="cab-icons">
                                    <a href="/payments/{{ $payment->id }}/edit" class="cab-icon cab-icon2"></a>
                                    <a href="#popup_payment_delete" class="cab-icon cab-icon3 fancybox payment-delete"></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @else
            <div class="add-project">
                <div class="add-project-img"><img src="{{ asset('assets/images/add-payment.png') }}" alt=""></div>
                <div class="add-project-title">У вас пока нет настроек оплаты</div>
                <div class="add-project-text">Настройте автоматизацию оплаты и добавления<br>пользователей в ABC Кабинет</div>
                <div class="add-project-button">
                    <a href="/payments/add" class="green-button">Добавить настройки оплаты</a>
                </div>
            </div>
            @endif
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<div id="popup_payment_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить вебинар "<span></span>"?</div>
    </div>
    <div class="popup-min-bottom">
        <a href="" class="red-button outline">Да</a>
        <button class="green-button close">Нет</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.material-table .cab-icon.payment-delete').on('click', function () {
            var id = $(this).closest("tr").data('id');
            var name = $(this).closest("tr").find(".table-title").text();

            $("#popup_payment_delete a.red-button").attr('href', "/payments/" + id + "/delete");
            $("#popup_payment_delete .popup-min-title span").text(name);
        });
        $(".datetime").datetimepicker({
            format: 'd.m.Y H:i',
            lang: 'ru',
            step: 30,
            dayOfWeekStart: 1,
            minDate: new Date(),
            minTime: new Date(),
            defaultDate: new Date()
        });
        var raw_offset = parseInt(new Date().getTimezoneOffset());
        var offset = -raw_offset * 60;

        $("input[name=offset]").val(offset);
    });
</script>
@endsection