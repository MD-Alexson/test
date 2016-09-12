<?php

Route::group(['middleware' => ['sid', 'auth:admin', 'admin', 'csrf'], 'namespace' => 'Admin', 'domain' => 'admin.'.config('app.domain')],
    function() {
    Route::get("/", function(){
        return redirect('/users');
    });
    
    Route::get('/upd', function(){
        $posts = \App\Post::all();
        foreach($posts as $post){
            switch ($post->thumbnail){
                case "https://abckabinet.ru/assets/images/thumbnails/posts/1.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/2.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/2.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/3.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/3.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/4.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/4.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/5.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/5.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/6.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/6.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/7.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/7.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/8.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/8.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/9.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/9.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/posts/10.png":
                    $post->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/posts/10.jpg";
                    break;
            }
            $post->save();
        }
        $cats = \App\Category::all();
        foreach ($cats as $cat){
            switch ($cat->thumbnail){
                case "https://abckabinet.ru/assets/images/thumbnails/categories/1.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/2.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/2.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/3.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/3.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/4.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/4.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/5.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/5.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/6.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/7.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/8.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/9.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
                case "https://abckabinet.ru/assets/images/thumbnails/categories/10.png":
                    $cat->thumbnail = "https://abckabinet.ru/assets/images/thumbnails/categories/1.jpg";
                    break;
            }
            $cat->save();
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

    Route::get('excel', function(){
        \Excel::create('ABC Users', function($excel) {
            $excel->sheet('ABC Users', function($sheet) {
                $sheet->row(1, ["Имя", "Email", "Телефон", "Тариф", "Статус", "Дата добавления", "Истекает"]);
                $count = 2;
                $sheet->setOrientation('landscape');
                foreach (\App\User::orderBy('created_at', 'desc')->get() as $user){
                    $sheet->row($count, [$user->name, $user->email, $user->phone, $user->plan->name, $user->status, $user->created_at, getDatetime($user->expires)]);
                    $count++;
                }
            });
        })->export('xls');
    });

    Route::get('/perpage/{perpage}', 'AccountController@perpage');
    Route::get("/logout", "AuthController@logout");
});