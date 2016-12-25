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

    Route::get('ipr', function() {

        $keys = Array(
            "P3DA-52BS-Y2CZ",
            "JKDW-8A43-C9RX",
            "RS5H-7V7U-CGR5",
            "JM4N-6U8A-CXRY",
            "RS5H-7V7U-CGR5",
            "KJMH-7V8B-C8RY",
            "M44F-4T5T-CSRZ",
            "KJMH-7V8B-C8RY",
            "P3DA-52BS-Y2CZ",
            "C9NB-5P5C-CYRP",
            "PNB8-6M7C-C3RZ",
            "JYXD-9R9M-CNRX",
            "XF8N-7Y93-CBRZ",
            "VNBC-7Q7C-C9RZ",
            "2966-2K3V-CXRF",
            "WPPS-B6YF-BEYA",
            "TM98-6M8Y-CAR7",
            "U95E-5S8U-CXR7",
            "LJQ8-7M4F-C8RZ",
            "GWSV-B9YL-AUZY",
            "92VG-5U8K-CRRM",
            "U95E-5S8U-CXR7",
            "P3DA-52BS-Y2CZ"
        );
        foreach ($keys as $key) {
            $ipr = DB::table('susers_ipr_keys')->where('key', $key)->first();
            $user = \App\Suser::findOrFail($ipr->suser_id);
            echo $user->name . " | " . $user->email . " | " . $key . "<br/>";
        }
        exit();
    });
});
