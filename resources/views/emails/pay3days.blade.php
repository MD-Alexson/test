<h3>Здравствуйте, {{ $user->name }}!</h3>
<p>Через 3 дня заканчивается срок действия вашего аккаунта <strong>ABC Кабинет</strong>.<br/>
Точное время прекращения доступа - <strong>{{ getDatetime($user->expires) }}</strong> (UTC-0)<br/>
Для оплаты и продления вашего аккаунта необходимо сделать следующее:</p>
<ol>
    <li>Используйте ваш логин и пароль для входа в ваш кабинет по ссылке "Войти" вверху сайта <a href="https://abckabinet.ru">https://abckabinet.ru</a></li>
    <li>Перейдите в раздел тарифов <a href="https://abckabinet.ru/account/plans">https://abckabinet.ru/account/plans</a></li>
    <li>Выберите тарифный план и произведите оплату удобным для вас способом.</li>
</ol>
<p>Напишите в нашу службу поддержки, если вам требуется помощь с оплатой: <a href="mailto:support@abckabinet.ru">support@abckabinet.ru</a></p>
<br/>--<br/>
<p>С уважением,<br/>
Техническая поддержка ABC Кабинет</p>

<a href="https://abckabinet.ru"><img src="{{ asset('assets/images/logo_header.png') }}" alt="logo"></a>

<p>Онлайн-сервис для автоматизации онлайн-обучения<br/>
    <a href="mailto:support@abckabinet.ru">support@abckabinet.ru</a> | <a href="https://abckabinet.ru">www.abckabinet.ru</a><br/>
+7 499 322-90-96</p>