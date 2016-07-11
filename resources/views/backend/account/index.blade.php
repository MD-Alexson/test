@extends('backend.app')
@section('content')
<section class="cabinet-holder">
    <section class="cabinet-content">
        @include('backend.inc.header')
        <div class="content-main">
            <div class="content-title">Настройки профиля</div>
            <div class="add-material">
                <div class="add-mat-title" style="border: 0px; padding: 0px; margin: 0px; line-height: 18px;">Данные аккаунта</div>
                <div class="account-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Тарифный план</th>
                                <th>Проектов</th>
                                <th>Пользователей</th>
                                <th>Использовано места</th>
                                <th>Оплачен до</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="/account/plans">{{ Auth::guard('backend')->user()->plan->name }}</a></td>
                                <td>{{ Auth::guard('backend')->user()->projects->count() }} из {{ Auth::guard('backend')->user()->plan->projects }}</td>
                                <td>{{ Auth::guard('backend')->user()->susers->count() }} из {{ Auth::guard('backend')->user()->plan->susers }}</td>
                                <td>{{ formatBytes(folderSize("/".Auth::guard('backend')->user()->id."/")) }} / {{ Auth::guard('backend')->user()->plan->space }} Гб</td>
                                <td><a href="/account/plans"><span class="toLocalTime onlydate">{{ getDatetime(Auth::guard('backend')->user()->expires) }}</span></a></td>
                                <td>
                                    @if(Auth::guard('backend')->user()->status)
                                    <span style="color: #39b54a">Активен</span>
                                    @else
                                    <span style="color: #cc0000">Деактивирован</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br/><br/>
                <form action="{{ action('Backend\AccountController@update') }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="add-mat-title">Личные данные</div>

                        <div class="add-mat-left">
                            <div class="add-mat-text">Ваше имя</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите ваше имя" required="required" value="{{ Auth::guard('backend')->user()->name }}" name="name" maxlength="32">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Ваш email</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="email" class="input" placeholder="Введите ваш email" required="required" value="{{ Auth::guard('backend')->user()->email }}" name="email" maxlength="32">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Ваш телефон</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Введите ваш телефон" value="{{ Auth::guard('backend')->user()->phone }}" name="phone" maxlength="20">
                        </div>
                        <div class="add-mat-title">Изменить пароль</div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Новый пароль</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="password" class="input" placeholder="Введите пароль" name="password" minlength="8" maxlength="20">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Новый пароль еще раз</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="password" class="input" placeholder="Введите пароль еще раз" name="password_check" minlength="8" maxlength="20">
                        </div>
                        <div class="add-mat-title">Ссылки</div>

                        <div class="add-mat-left">
                            <div class="add-mat-text">Сайт</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Ссылка" value="{{ Auth::guard('backend')->user()->site }}" name="site" maxlength="40">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">VKontakte</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Ссылка" value="{{ Auth::guard('backend')->user()->vk }}" name="vk" maxlength="40">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">Facebook</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Ссылка" value="{{ Auth::guard('backend')->user()->fb }}" name="fb" maxlength="40">
                        </div>
                        <div class="add-mat-left">
                            <div class="add-mat-text">LinkedIn</div>
                        </div>
                        <div class="add-mat-right">
                            <input type="text" class="input" placeholder="Ссылка" value="{{ Auth::guard('backend')->user()->linkedin }}" name="linkedin" maxlength="40">
                        </div>
                        
                        <div class="add-mat-title"></div>
                        <div class="add-mat-right-holder">
                            <button class="green-button float-left" type="submit">Сохранить настройки</button>
                            <a href="#popup_account_delete" class="grey-button float-right fancybox">Удалить профиль</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
</section>
<script type='text/javascript'>
    $(document).ready(function () {
        $("input[name=image]").on('change', function () {
            $("p.image_path").text($(this).val());
        });
    });
</script>
<style>
    .material-table tr td, .material-table tr th {
        text-align: center;
    }
</style>
@include('backend.inc.sidebar')
<div id="popup_account_delete" class="popup-min popup-delete">
    <div class="popup-min-top">
        <div class="popup-min-title">Вы уверены что хотите удалить аккаунт?</div>
    </div>
    <div class="popup-min-content">
        <div class="popup-min-text">При этом удалятся все ваши проекты, их пользователи и файлы!</div>
        <div class='popup-min-text'>
            <form action='{{ action('Backend\AccountController@delete') }}' method="post" id='form_account_delete'>
                {{ csrf_field() }}
                <input style='margin: 0 auto' type="password" class="input" placeholder="Введите пароль" name="password" required="required" minlength="8" maxlength="20">
            </form>
        </div>
    </div>
    <div class="popup-min-bottom">
        <input type='submit' form="form_account_delete" class="red-button outline" value='Да'>
        <button class="green-button close">Нет</button>
    </div>
</div>
@endsection