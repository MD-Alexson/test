<div id="info1" class="window info_window">
    <div class="info_mask"></div>
    <div class="info_scroll">
        <div class="info_popup">
            <h2>Подключайте платежные системы<a href='' class='close'></a></h2>
            <div class="info_popup_screen">
                <img src="{{ asset('assets/images/media/info1_screen.jpg') }}">
                
            </div>
            <div class="info_popup_info">
                <h3>Описание сервиса</h3>
                <p>Подключайте популярные системы приема платежей. Интегрируйте в ваш кабинет агрегаторы платежных систем justclick или e-autopay при помощи нескольких кликов.</p>
            </div>
            <form action="{{ action('Shared\AuthController@register') }}" method="post">
                {{ csrf_field() }}
                <h3>Регистрация</h3>
                <div>
                    <label>Ваше имя</label>
                    <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш e-mail</label>
                    <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш номер телефона</label>
                    <input name='phone' type="text" class="input" placeholder="Введите телефон" required="required" maxlength="20">
                </div>
                <div style="width: 25%; margin-right: 0%;">
                    <label>&nbsp;</label>
                    <button class="green-button">Регистрация</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<div id="info2" class="window info_window">
    <div class="info_mask"></div>
    <div class="info_scroll">
        <div class="info_popup">
            <h2>Проводите вебинары<a href='' class='close'></a></h2>
            <div class="info_popup_screen">
                <img src="{{ asset('assets/images/media/info2_screen.jpg') }}">
                
            </div>
            <div class="info_popup_info">
                <h3>Описание сервиса</h3>
                <p> Проводите вебинары. Настройте трансляцию вебинара с помощью вставки специального кода, запланируйте дату и время проведения. Укажите действие по таймеру с момента начала вебинара, например, появление кнопки или ссылки.</p>
            </div>
            <form action="{{ action('Shared\AuthController@register') }}" method="post">
                {{ csrf_field() }}
                <h3>Регистрация</h3>
                <div>
                    <label>Ваше имя</label>
                    <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш e-mail</label>
                    <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш номер телефона</label>
                    <input name='phone' type="text" class="input" placeholder="Введите телефон" required="required" maxlength="20">
                </div>
                <div style="width: 25%; margin-right: 0%;">
                    <label>&nbsp;</label>
                    <button class="green-button">Регистрация</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<div id="info3" class="window info_window">
    <div class="info_mask"></div>
    <div class="info_scroll">
        <div class="info_popup">
            <h2>Добавляйте вложения и домашние задания<a href='' class='close'></a></h2>
            <div class="info_popup_screen">
                <img src="{{ asset('assets/images/media/info3_screen.jpg') }}">
                
            </div>
            <div class="info_popup_info">
                <h3>Описание сервиса</h3>
                <p>Разместите документы PDF, DOC, XLS, MP4, MP3 и другие форматы для возможности скачивания их вашим клиентам. Добавляйте задания к материалам и проверяйте их выполнение.</p>
            </div>
            <form action="{{ action('Shared\AuthController@register') }}" method="post">
                {{ csrf_field() }}
                <h3>Регистрация</h3>
                <div>
                    <label>Ваше имя</label>
                    <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш e-mail</label>
                    <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш номер телефона</label>
                    <input name='phone' type="text" class="input" placeholder="Введите телефон" required="required" maxlength="20">
                </div>
                <div style="width: 25%; margin-right: 0%;">
                    <label>&nbsp;</label>
                    <button class="green-button">Регистрация</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<div id="info4" class="window info_window">
    <div class="info_mask"></div>
    <div class="info_scroll">
        <div class="info_popup">
            <h2>Продавайте продукты с подпиской<a href='' class='close'></a></h2>
            <div class="info_popup_screen">
                <img src="{{ asset('assets/images/media/info4_screen.jpg') }}">
                
            </div>
            <div class="info_popup_info">
                <h3>Описание сервиса</h3>
                <p>Продавайте продукты с регулярной оплатой один раз в неделю, месяц, год или другой удобный период. Настройте оповещения клиентам по окончании оплаченного периода.</p>
            </div>
            <form action="{{ action('Shared\AuthController@register') }}" method="post">
                {{ csrf_field() }}
                <h3>Регистрация</h3>
                <div>
                    <label>Ваше имя</label>
                    <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш e-mail</label>
                    <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш номер телефона</label>
                    <input name='phone' type="text" class="input" placeholder="Введите телефон" required="required" maxlength="20">
                </div>
                <div style="width: 25%; margin-right: 0%;">
                    <label>&nbsp;</label>
                    <button class="green-button">Регистрация</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<div id="info5" class="window info_window">
    <div class="info_mask"></div>
    <div class="info_scroll">
        <div class="info_popup">
            <h2>Управляйте пользователями и уровнями доступа<a href='' class='close'></a></h2>
            <div class="info_popup_screen">
                <img src="{{ asset('assets/images/media/info5_screen.jpg') }}">
                
            </div>
            <div class="info_popup_info">
                <h3>Описание сервиса</h3>
                <p>Добавьте, импортируйте или экспортируйте пользователей из CSV таблицы. Создавайте права доступа для отдельных пользователей с различными возможностями.</p>
            </div>
            <form action="{{ action('Shared\AuthController@register') }}" method="post">
                {{ csrf_field() }}
                <h3>Регистрация</h3>
                <div>
                    <label>Ваше имя</label>
                    <input name='name' type="text" class="input" placeholder="Введите имя" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш e-mail</label>
                    <input name='email' type="email" class="input" placeholder="Введите email" required="required" maxlength="32">
                </div>
                <div>
                    <label>Ваш номер телефона</label>
                    <input name='phone' type="text" class="input" placeholder="Введите телефон" required="required" maxlength="20">
                </div>
                <div style="width: 25%; margin-right: 0%;">
                    <label>&nbsp;</label>
                    <button class="green-button">Регистрация</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>