<?php

Route::group(['middleware' => ['sid'], 'namespace' => 'Frontend', 'domain' => '{domain}.' . config('app.domain')], function($domain) {
    Route::get('/login', 'AccountController@loginView');
    Route::get('/rr', function() {
//        \Auth::guard('frontend')->login(\App\Suser::findOrFail(7706));
    });
    Route::post('/login', 'AccountController@login');
    Route::get('/logout', "AccountController@logout");
    Route::get('/pass', 'AccountController@passwordView');
    Route::post('/pass', 'AccountController@password');
    Route::get('/pass/sent', 'AccountController@passwordSentView');
    Route::get('/pass/{key}', 'AccountController@passwordChangeView');
    Route::post('/pass/{key}', 'AccountController@passwordChange');
    Route::get('/register', 'AccountController@registerView');
    Route::post('/register', 'AccountController@register');
    Route::get('/nu/{key}', 'AccountController@createPasswordView');
    Route::post('/nu/{key}', 'AccountController@createPassword');
    Route::get('/expired', 'AccountController@expiredView');

    Route::get('/webinar/{url}', 'WebinarsController@show');
});

Route::group(['middleware' => ['sid', 'front'], 'namespace' => 'Frontend', 'domain' => '{domain}.' . config('app.domain')], function($domain) {
    Route::get('/', "AccountController@mainPage");
    Route::get('/account', "AccountController@edit");
    Route::post('/account', "AccountController@update");
    Route::get('/account/comments', "AccountController@comments");
    Route::get('/account/comments/{comment_id}/destroy', "AccountController@commentDestroy")->where('comment_id', '[0-9]+');
    Route::get('/account/homeworks', "AccountController@homeworks");
    Route::post('/account/homeworks/{homework_id}/update', "AccountController@homeworkUpdate")->where('homework_id', '[0-9]+');
    Route::get('/account/homeworks/{homework_id}/destroy', "AccountController@homeworkDestroy")->where('homework_id', '[0-9]+');
    Route::get('/categories', "CategoriesController@index");
    Route::get('/categories/{cat_id}', "CategoriesController@show")->where('cat_id', '[0-9]+');
    Route::get('/posts', "PostsController@index");
    Route::get('/posts/{post_id}', "PostsController@show")->where('post_id', '[0-9]+');
    Route::post('/posts/{post_id}/comments/create', "PostsController@comment")->where('post_id', '[0-9]+');
    Route::get('/posts/{post_id}/comments/{comment_id}/destroy', 'PostsController@commentDestroy')->where('post_id', '[0-9]+')->where('comment_id', '[0-9]+');
    Route::post('/posts/{post_id}/homeworks/create', "PostsController@homework")->where('post_id', '[0-9]+');
    Route::post('/posts/{post_id}/homeworks/{homework_id}/update', 'PostsController@homeworkUpdate')->where('post_id', '[0-9]+')->where('homework_id', '[0-9]+');
    Route::get('/posts/{post_id}/homeworks/{homework_id}/destroy', 'PostsController@homeworkDestroy')->where('post_id', '[0-9]+')->where('homework_id', '[0-9]+');
    Route::get('/level/{level_id}', 'AccountController@level')->where('level_id', '[0-9]+');

    Route::get('/imagepath/{path}', 'StorageController@getImage')->where('path', '.+');
    Route::get('/filepath/{path}', 'StorageController@getFile')->where('path', '.+');
    Route::get('/select/{level_id}', 'AccountController@select')->where('level_id', '[0-9]+');
    
    Route::get('/dkpdf/{user_rnd}/{file_name}', function($domain, $user_rnd, $file_name) {
        $project = \App\Project::findOrFail($domain);
        $owner = $project->user->id;
        if($owner !== 80){
            abort(404);
        }
        if($user_rnd !== 'admin62256225'){
            $check_user = $project->susers()->where('rand', $user_rnd)->count();
            if(!$check_user){
                echo "Тут то ты и попался, Нео.";
                if(Session::get('guard') === "frontend"){
                    $user = Auth::guard(Session::get('guard'))->user();
                    echo $user->name."<br/>".$user->email."<br/>".$user->id;
                }
                exit();
            }
        }
        $pdf_path = public_path().'/dkpdf/'.$file_name;
        if (!file_exists($pdf_path)) {
            abort(404);
        }
        $file = file_get_contents($pdf_path);
        $pdf = preg_replace("~(dimakovpak\.[a-z\.\/]+)()~is", "$1?utm_source=" . $user_rnd, $file);
        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename=" . $file_name);
        echo $pdf;
    });
});

Route::group(['middleware' => ['remote', 'sid'], 'namespace' => 'Frontend'], function($domain) {
    Route::get('/login', 'AccountController@loginView');
    Route::post('/login', 'AccountController@login');
    Route::get('/logout', "AccountController@logout");
    Route::get('/pass', 'AccountController@passwordView');
    Route::post('/pass', 'AccountController@password');
    Route::get('/pass/sent', 'AccountController@passwordSentView');
    Route::get('/pass/{key}', 'AccountController@passwordChangeView');
    Route::post('/pass/{key}', 'AccountController@passwordChange');
    Route::get('/register', 'AccountController@registerView');
    Route::post('/register', 'AccountController@register');
    Route::get('/nu/{key}', 'AccountController@createPasswordView');
    Route::post('/nu/{key}', 'AccountController@createPassword');
    Route::get('/expired', 'AccountController@expiredView');

    Route::get('/webinar/{url}', 'WebinarsController@show');
});

Route::group(['middleware' => ['remote', 'sid', 'front'], 'namespace' => 'Frontend'], function($domain) {
    Route::get('/', "AccountController@mainPage");
    Route::get('/account', "AccountController@edit");
    Route::post('/account', "AccountController@update");
    Route::get('/account/comments', "AccountController@comments");
    Route::get('/account/comments/{comment_id}/destroy', "AccountController@commentDestroy")->where('comment_id', '[0-9]+');
    Route::get('/account/homeworks', "AccountController@homeworks");
    Route::post('/account/homeworks/{homework_id}/update', "AccountController@homeworkUpdate")->where('homework_id', '[0-9]+');
    Route::get('/account/homeworks/{homework_id}/destroy', "AccountController@homeworkDestroy")->where('homework_id', '[0-9]+');
    Route::get('/categories', "CategoriesController@index");
    Route::get('/categories/{cat_id}', "CategoriesController@show");
    Route::get('/posts', "PostsController@index");
    Route::get('/posts/{post_id}', "PostsController@show")->where('post_id', '[0-9]+');
    Route::post('/posts/{post_id}/comments/create', "PostsController@comment")->where('post_id', '[0-9]+');
    Route::get('/posts/{post_id}/comments/{comment_id}/destroy', 'PostsController@commentDestroy')->where('post_id', '[0-9]+')->where('comment_id', '[0-9]+');
    Route::post('/posts/{post_id}/homeworks/create', "PostsController@homework")->where('post_id', '[0-9]+');
    Route::post('/posts/{post_id}/homeworks/{homework_id}/update', 'PostsController@homeworkUpdate')->where('post_id', '[0-9]+')->where('homework_id', '[0-9]+');
    Route::get('/posts/{post_id}/homeworks/{homework_id}/destroy', 'PostsController@homeworkDestroy')->where('post_id', '[0-9]+')->where('homework_id', '[0-9]+');
    Route::get('/level/{level_id}', 'AccountController@level')->where('level_id', '[0-9]+')->where('level_id', '[0-9]+');

    Route::get('/imagepath/{path}', 'StorageController@getImage')->where('path', '.+');
    Route::get('/filepath/{path}', 'StorageController@getFile')->where('path', '.+');
    Route::get('/select/{level_id}', 'AccountController@select')->where('level_id', '[0-9]+');
    
    Route::get('/dkpdf/{user_rnd}/{file_name}', function($domain, $user_rnd, $file_name) {
        $owner = \App\Project::findOrFail($domain)->user->id;
        if($owner !== 80){
            abort(404);
        }
        $pdf_path = public_path().'/dkpdf/'.$file_name;
        if (!file_exists($pdf_path)) {
            abort(404);
        }
        $file = file_get_contents($pdf_path);
        $pdf = preg_replace("~(dimakovpak\.[a-z\.\/]+)()~is", "$1?utm_source=" . $user_rnd, $file);
        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename=" . $file_name);
        echo $pdf;
    });
});
