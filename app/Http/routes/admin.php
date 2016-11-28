<?php

Route::group(['middleware' => ['sid', 'auth:admin', 'admin', 'csrf'], 'namespace' => 'Admin', 'domain' => 'admin.' . config('app.domain')], function() {
    Route::get("/", function() {
        return redirect('/users');
    });

    Route::get('/not', function() {
        foreach (\App\User::where('status', true)->get() as $user) {
            $not = new \App\Notification();
            $not->name = "Обновление дизайна пользовательской части";
            $not->text = "Рады сообщить вам об обновлении дизайна пользовательской части. Обновился внешний вид списка категорий и публикаций, изменился стандартный размер миниатюр, появилась возможность добавлять атрибут к публикации, который будет выглядеть как перекрывающая плашка на миниатюре и остальные мелочи. Если у вас возникнут трудности с сервисом или вы нашли баги — вы всегда можете обратиться в нашу службу поддержки, написав нам письмо: <a href='mailto:support@abckabinet.ru'>support@abckabinet.ru</a>";
            $not->user()->associate($user);
            $not->save();
        }
    });

    Route::get("/users", "UsersController@index");
    Route::get("/users/sort/{order_by}/{order}", "UsersController@sort");
    Route::get('/users/add', 'UsersController@add');
    Route::post('/users/store', 'UsersController@store');
    Route::get('/users/{user_id}/view', 'UsersController@view');
    Route::get('/users/{user_id}/edit', 'UsersController@edit');
    Route::get('/users/{user_id}/data', 'UsersController@data');
    Route::post('/users/{user_id}/update', 'UsersController@update');
    Route::get('/users/{user_id}/delete', 'UsersController@delete');
    Route::post('/users/batch', 'UsersController@batch');
    Route::post('/users/filters', 'UsersController@filters');
    Route::get('/users/filters/reset', 'UsersController@filtersReset');
    Route::get('/users/search/', 'UsersController@search');

    Route::get('excel', function() {
        \Excel::create('ABC Users', function($excel) {
            $excel->sheet('ABC Users', function($sheet) {
                $sheet->row(1, ["Имя", "Email", "Телефон", "Тариф", "Статус", "Дата добавления", "Истекает"]);
                $count = 2;
                $sheet->setOrientation('landscape');
                foreach (\App\User::orderBy('created_at', 'desc')->get() as $user) {
                    $sheet->row($count, [$user->name, $user->email, $user->phone, $user->plan->name, $user->status, $user->created_at, getDatetime($user->expires)]);
                    $count++;
                }
            });
        })->export('xls');
    });

    Route::get('/perpage/{perpage}', 'AccountController@perpage');
    Route::get("/logout", "AuthController@logout");

    Route::get('/copyk17', function() {
        $k17users = \App\Project::findOrFail("k17")->susers;
        
        $tmp_level = \App\Level::findOrFail(10174);
        $tmp_project = \App\Project::findOrFail("intensiv2016");
        
        foreach ($k17users as $k17user) {

            $tmp_check = $tmp_project->susers()->where('email', $k17user->email)->first();
            if (!count($tmp_check)) {
                $user = new Suser();
                $user->name = $k17user->name;
                $user->email = $k17user->email;
                $user->phone = $k17user->phone;
                $user->expires = $k17user->expires;
                $user->password = $k17user->password;
                $user->password_raw = $k17user->password_raw;
                $user->rand = str_random(16);
                $user->level()->associate($tmp_level);
                $user->project()->associate($tmp_project);
                $user->save();

                $sub = "Интенсив Димы Ковпака - Доступы";
                $msg = "Здравстуйте, {username}!\r
Ваши доступы к интенсиву:\r
Ссылка:\r
http://intensiv2016.abckabinet.ru/login\r
\r
Email:\r
{email}\r
\r
Пароль:\r
{pass}\r
\r
Проблемы с доступом? Пишите:\r
support@abckabinet.ru\r
\r
Благодарим Вас за покупку!\r
\r
-----------\r
\r";
                $msg = str_replace("{username}", $user->name, $msg);
                $msg = str_replace("{email}", $user->email, $msg);
                $msg = str_replace("{pass}", $user->password_raw, $msg);

                Mail::raw($msg, function($message) use ($user, $sub) {
                    $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                    $message->to($user->email)->subject($sub);
                });
            }
        }
    });

    Route::get('/intense', function() {
        $project = \App\Project::findOrFail("intensiv2016");
        $users = $project->susers;

        foreach ($users as $user) {
            $sub = "Интенсив Димы Ковпака - Доступы";
            $msg = "Здравстуйте, {username}!\r
Ваши доступы к интенсиву:\r
Ссылка:\r
http://intensiv2016.abckabinet.ru/login\r
\r
Email:\r
{email}\r
\r
Пароль:\r
{pass}\r
\r
Проблемы с доступом? Пишите:\r
support@abckabinet.ru\r
\r
Благодарим Вас за покупку!\r
\r
-----------\r
\r";
            $msg = str_replace("{username}", $user->name, $msg);
            $msg = str_replace("{email}", $user->email, $msg);
            $msg = str_replace("{pass}", $user->password_raw, $msg);

            Mail::raw($msg, function($message) use ($user, $sub) {
                $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                $message->to($user->email)->subject($sub);
            });
        }
    });
});
