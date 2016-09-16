@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-back">
                <a href="/payments">Вернуться к настройкам оплаты</a>
            </div>
            
            <div class="add-material">
                <form action="{{ action('Backend\PaymentsController@store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="material-note">
                            Настройки оплаты позволяют автоматически дабавлять пользователей<br/>
                            в ваш проект в ABC Кабинет сразу же после оплаты ваших продуктов.<br/><br/>
                            Возникли трудности или нужна помощь в настройке? <a href="#popup_message" class="fancybox message">Написать в поддержку</a>
                        </div>
                        <div class="add-mat-title">Основная информация</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Платежная система</div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="method">
                                    <option value="E-Autopay">E-Autopay</option>
                                    <option value="Justclick">Justclick</option>
                                    <option value="Fondy">Fondy</option>
                                </select>
                            </div>
                        </div>
                        <div class="material-note" id="changeble_note">ВАЖНО! В настройках продукта в E-Autopay укажите следующий URL<br/>для уведомлений об оплате заказа:<br/><strong>{{ config('app.url') }}/api/payment/eautopay</strong></div>
                        <div class="add-mat-left merch_id" style='display: none'>
                            <div class="add-mat-text tooltip-holder">ID Мерчанта</div>
                        </div>
                        <div class="add-mat-right merch_id" style='display: none'>
                            <input type="text" class="input" placeholder="Введите ID" name="merch_id" maxlength="24" value="—" required="required">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Пароль</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите пароль" name="key" required="required" maxlength="128" value="{{ Request::old('key') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">ID товара в системе <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Введите ID товара</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите id" name="item_id" required="required" maxlength="40" value="{{ Request::old('item_id') }}">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Уровень доступа пользователя <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Уровень доступа, который получит пользователь после оплаты</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="select-block">
                                <select class="styled" name="level_id">
                                    @foreach($project->levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text tooltip-holder">Membership-доступ <span class="tooltip-icon" data-content="<div class='popover-inner-text'>По истечения указанного периода после первой оплаты пользователю необходимо будет оплатить membership-доступ (подписку) повторно на указанный вами период</div>">?</span></div>
                        </div>
                        <div class="add-mat-right">
                            <div class="check-list">
                                <div class="check-block">
                                    <input name="membership" type="checkbox" class="check" id="ch2">
                                    <label for="ch2" class="check-label">Включить membership-доступ пользователю к проекту после оплаты на период:</label>
                                    <br/>
                                    <br/>
                                    <input type="number" class="input inline-button" placeholder="Число" name="membership_num" value="1" min="1" style="width: 80px;">
                                    <div class="select-block inline-button">
                                        <select class="styled" name="membership_type">
                                            <option value="months">Месяцев</option>
                                            <option value="weeks">Недель</option>
                                            <option value="days">Дней</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-mat-optional-title">Настройки email-уведомлений</div>
                        <div id="optional">
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Письмо после первой оплаты <span class="tooltip-icon" data-content="<div class='popover-inner-text'>Данное письмо придет на почту пользователю, совершившему оплату в первый раз.</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <p class='tooltip-holder'>Тема письма: <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Если оставить поле пустым, письма пользователям отправлены не будут.<br/><br/>Список доступных переменных:<br/>{username} - Имя пользователя<br/>{pass_link} - Ссылка на создание\напоминанние пароля<br/>{email} - email пользователя<br/>{project_name} - Название вашего проекта<br/>{level_name} - Название уровня доступа</div>">?</span></p>
                                <input type="text" class="input input-long" name="subject" required="required" maxlength="255" value="{project_name} - Создайте пароль!">
                                <p class='tooltip-holder'>Текст письма: <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Важно: в случае первой оплаты текст письма должен содержать переменную {pass_link}, которая в письме будет автоматически отображена в виде ссылки, чтобы пользователь мог создать свой пароль перейдя по ней. Если оставить поле пустым, письма пользователям отправлены не будут.<br/><br/>Список доступных переменных:<br/>{username} - Имя пользователя<br/>{pass_link} - Ссылка на создание\напоминанние пароля<br/>{email} - email пользователя<br/>{project_name} - Название вашего проекта<br/>{level_name} - Название уровня доступа</div>">?</span></p>
                                <textarea name="message" class="textarea">Здравстуйте, {username}!
Все, что Вам осталось сделать для
пользования проектом - создать пароль:

{pass_link}

Если по какой-то причине Вам не удалось
создать пароль для входа - обязательно
напишите нам - support@abckabinet.ru

Благодарим Вас за покупку!

-----------

{project_name}</textarea>
                            </div>
                            <div class="add-mat-left">
                                <div class="add-mat-text tooltip-holder">Письмо после повторной оплаты: <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Данное письмо придет на почту пользователю, совершившему оплату не в первый раз.</div>">?</span></div>
                            </div>
                            <div class="add-mat-right">
                                <p class='tooltip-holder'>Тема письма: <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>Если оставить поле пустым, письма пользователям отправлены не будут.<br/><br/>Список доступных переменных:<br/>{username} - Имя пользователя<br/>{pass_link} - Ссылка на создание\напоминанние пароля<br/>{email} - email пользователя<br/>{project_name} - Название вашего проекта<br/>{level_name} - Название уровня доступа</div>">?</span></p>
                                <input type="text" class="input input-long" name="subject2" required="required" maxlength="255" value="{project_name} - Создайте пароль!">
                                <p class='tooltip-holder'>Текст письма: <span class="tooltip-icon tooltip-icon-inline" data-content="<div class='popover-inner-text'>При повторной оплате пользователем переменная {pass_link} позволит вспомнить его пароль при необходимости.Если оставить поле пустым, письма пользователям отправлены не будут.<br/><br/>Список доступных переменных:<br/>{username} - Имя пользователя<br/>{pass_link} - Ссылка на создание\напоминанние пароля<br/>{email} - email пользователя<br/>{project_name} - Название вашего проекта<br/>{level_name} - Название уровня доступа</div>">?</span></p>
                                <textarea name="message2" class="textarea">Здравстуйте, {username}!
Вы успешно продлили пользование
проектом {project_name}!

Забыли пароль?
{pass_link}

-----------

{project_name}</textarea>
                            </div>
                        </div>
                        <br/>
                        <div class="add-mat-right-holder">
                            <button class="green-button float-left" type="submit">Сохранить</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
</section>
@include('backend.inc.sidebar')
<script type="text/javascript">
    $(document).ready(function(){
        var chnote1 = "ВАЖНО! В настройках продукта в E-Autopay укажите следующий URL<br/>для уведомлений об оплате заказа:<br/><strong>{{ config('app.url') }}/api/payment/eautopay</strong>";
        var chnote2 = "ВАЖНО! В настройках продукта в Justclick укажите следующий URL<br/>для уведомлений об оплате заказа:<br/><strong>{{ config('app.url') }}/api/payment/justclick</strong>";
        var chnote3 = "ВАЖНО! В настройках кнопки / в генераторе в Fondy укажите следующие URL<br/>для уведомлений об оплате заказа (Callback/Response URL) и если необходимо<br/>subscription_callback_url для регулярных платежей:<br/><strong>{{ config('app.url') }}/api/payment/fondy</strong>";
        
        $("select[name=method]").on('selectmenuselect', function(){
            if($(this).val() === 'E-Autopay'){
                $("#changeble_note").html(chnote1);
                $('.merch_id').slideUp();
            } else if($(this).val() === 'Justclick'){
                $("#changeble_note").html(chnote2);
                $('.merch_id').slideUp();
            } else if($(this).val() === 'Fondy'){
                $("#changeble_note").html(chnote3);
                $('.merch_id').slideDown();
            }
        });
    });
</script>
@endsection