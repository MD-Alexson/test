<?php

Route::group(['middleware' => ['sid', 'auth:backend', 'csrf'], 'namespace' => 'Backend', 'domain' => config('app.domain')], function() {

    Route::get('/account', 'AccountController@index');
    Route::post('/account', 'AccountController@update');
    Route::get('/account/plans', 'AccountController@plans');
    Route::get('/account/payment', 'AccountController@payment');
    Route::get('/account/logout', 'AccountController@logout');
    Route::post('/account/delete', 'AccountController@delete');
    Route::post('/account/message', 'AccountController@message');
    Route::get('/account/faq', 'AccountController@faq');
    Route::get('/account/expired', 'AccountController@expired');

    Route::get('/projects', 'ProjectsController@index')->middleware(['status']);
    Route::get('/dashboard', 'ProjectsController@dashboard')->middleware(['status', 'project']);
    Route::get('/projects/add', 'ProjectsController@add')->middleware(['status', 'limits:projects']);
    Route::post('/projects/store', 'ProjectsController@store')->middleware(['status', 'limits:projects']);
    Route::get('/settings', 'ProjectsController@edit')->middleware(['status', 'project']);
    Route::post('/settings', 'ProjectsController@update')->middleware(['status', 'project']);
    Route::post('/settings/delete', 'ProjectsController@delete')->middleware(['status', 'project']);

    Route::get('/webinars', 'WebinarsController@index')->middleware(['status', 'project']);
    Route::get('/webinars/add', 'WebinarsController@add')->middleware(['status', 'project']);
    Route::post('/webinars/store', 'WebinarsController@store')->middleware(['status', 'project']);
    Route::get('/webinars/{webinar_id}/edit', 'WebinarsController@edit')->middleware(['status', 'project']);
    Route::post('/webinars/{webinar_id}/update', 'WebinarsController@update')->middleware(['status', 'project']);
    Route::get('/webinars/{webinar_id}/delete', 'WebinarsController@delete')->middleware(['status', 'project']);
    Route::post('/webinars/batch', 'WebinarsController@batch')->middleware(['status', 'project']);

    Route::get('/categories', 'CategoriesController@index')->middleware(['status', 'project']);
    Route::get('/categories/order', 'CategoriesController@orderView')->middleware(['status', 'project']);
    Route::get('/categories/order_change', 'CategoriesController@order')->middleware(['status', 'project']);
    Route::get('/categories/by_level/{level_id}', 'CategoriesController@indexByLevel')->middleware(['status', 'project']);
    Route::get('/categories/add', 'CategoriesController@add')->middleware(['status', 'project']);
    Route::get('/categories/add/level/{level_id}', 'CategoriesController@add')->middleware(['status', 'project'])->where('level_id', '[0-9]+');
    Route::post('/categories/store', 'CategoriesController@store')->middleware(['status', 'project']);
    Route::get('/categories/{cat_id}/edit', 'CategoriesController@edit')->middleware(['status', 'project']);
    Route::post('/categories/{cat_id}/update', 'CategoriesController@update')->middleware(['status', 'project']);
    Route::get('/categories/{cat_id}/delete', 'CategoriesController@delete')->middleware(['status', 'project']);
    Route::post('/categories/batch', 'CategoriesController@batch')->middleware(['status', 'project']);

    Route::get('/posts', 'PostsController@index')->middleware(['status', 'project']);
    Route::get('/posts/order', 'PostsController@orderAllView')->middleware(['status', 'project']);
    Route::get('/posts/order_change', 'PostsController@orderAll')->middleware(['status', 'project']);
    Route::get('/posts/by_category/{cat_id}', 'PostsController@indexByCategory')->middleware(['status', 'project']);
    Route::get('/posts/by_category/{cat_id}/order', 'PostsController@orderView')->middleware(['status', 'project']);
    Route::get('/posts/by_category/{cat_id}/order_change', 'PostsController@order')->middleware(['status', 'project']);
    Route::get('/posts/by_level/{level_id}', 'PostsController@indexByLevel')->middleware(['status', 'project']);
    Route::get('/posts/add', 'PostsController@add')->middleware(['status', 'project']);
    Route::get('/posts/add/{with}/{with_id}', 'PostsController@add')->middleware(['status', 'project'])->where('with_id', '[0-9]+');
    Route::post('/posts/store', 'PostsController@store')->middleware(['status', 'project']);
    Route::get('/posts/{post_id}/edit', 'PostsController@edit')->middleware(['status', 'project']);
    Route::post('/posts/{post_id}/update', 'PostsController@update')->middleware(['status', 'project']);
    Route::get('/posts/{post_id}/delete', 'PostsController@delete')->middleware(['status', 'project']);
    Route::post('/posts/batch', 'PostsController@batch')->middleware(['status', 'project']);

    Route::get('/levels', 'LevelsController@index')->middleware(['status', 'project']);
    Route::post('/levels/store', 'LevelsController@store')->middleware(['status', 'project']);
    Route::post('/levels/{level_id}/update', 'LevelsController@update')->middleware(['status', 'project']);
    Route::get('/levels/{level_id}/delete', 'LevelsController@delete')->middleware(['status', 'project']);
    Route::post('/levels/batch/', 'LevelsController@batch')->middleware(['status', 'project']);

    Route::get('/users', 'SusersController@index')->middleware(['status', 'project']);
    Route::get('/users/data', 'SusersController@dataAll')->middleware(['status', 'project']);
    Route::get('/users/by_level/{level_id}', 'SusersController@indexByLevel')->middleware(['status', 'project']);
    Route::get('/users/by_level/{level_id}/data', 'SusersController@dataByLevel')->middleware(['status', 'project']);
    Route::get('/users/add', 'SusersController@add')->middleware(['status', 'project']);
    Route::post('/users/store/', 'SusersController@store')->middleware(['status', 'project']);
    Route::get('/users/{user_id}/edit/', 'SusersController@edit')->middleware(['status', 'project']);
    Route::get('/users/{user_id}/data', 'SusersController@data')->middleware(['status', 'project']);
    Route::post('/users/{user_id}/update', 'SusersController@update')->middleware(['status', 'project']);
    Route::get('/users/{user_id}/delete', 'SusersController@delete')->middleware(['status', 'project']);
    Route::post('/users/batch', 'SusersController@batch')->middleware(['status', 'project']);
    Route::get('/users/import', 'SusersController@importView')->middleware(['status', 'project']);
    Route::post('/users/import', 'SusersController@import')->middleware(['status', 'project']);
    Route::post('/users/import/manual', 'SusersController@importManual')->middleware(['status', 'project']);
    Route::get('/users/export', 'SusersController@exportView')->middleware(['status', 'project']);
    Route::get('/users/export/csv', 'SusersController@exportCSV')->middleware(['status', 'project']);
    Route::get('/users/export/xls', 'SusersController@exportXLS')->middleware(['status', 'project']);
    Route::get('/users/search/', 'SusersController@search')->middleware(['status', 'project']);
    

    Route::get('/imagepath/{path}', 'StorageController@getImage')->where('path', '.+')->middleware(['project']);
    Route::get('/filepath/{path}', 'StorageController@getFile')->where('path', '.+')->middleware(['project']);
    Route::get('/select/{project}/{redirect?}', 'ProjectsController@select')->middleware(['ownership']);
    Route::get('/perpage/{perpage}', 'AccountController@perpage');

    Route::get('/emails', 'EmailController@index')->middleware(['status', 'project']);
    Route::get('/emails/add', 'EmailController@add')->middleware(['status', 'project']);
    Route::post('/emails/store', 'EmailController@store')->middleware(['status', 'project']);
    Route::get('/emails/{email_id}/edit', 'EmailController@edit')->middleware(['status', 'project']);
    Route::post('/emails/{email_id}/update', 'EmailController@update')->middleware(['status', 'project']);
    Route::get('/emails/{email_id}/delete', 'EmailController@delete')->middleware(['status', 'project']);
    Route::post('/emails/batch', 'EmailController@batch')->middleware(['status', 'project']);

    Route::get('/payments', 'PaymentsController@index')->middleware(['status', 'project']);
    Route::get('/payments/add', 'PaymentsController@add')->middleware(['status', 'project']);
    Route::post('/payments/store', 'PaymentsController@store')->middleware(['status', 'project']);
    Route::get('/payments/{payment_id}/edit', 'PaymentsController@edit')->middleware(['status', 'project']);
    Route::post('/payments/{payment_id}/update', 'PaymentsController@update')->middleware(['status', 'project']);
    Route::get('/payments/{payment_id}/delete', 'PaymentsController@delete')->middleware(['status', 'project']);
    Route::post('/payments/batch', 'PaymentsController@batch')->middleware(['status', 'project']);
    Route::get('/payments', 'PaymentsController@index')->middleware(['status', 'project']);
    
    Route::get('/ipr', 'IprController@index')->middleware(['status', 'project']);
    Route::get('/ipr/add', 'IprController@add')->middleware(['status', 'project']);
    Route::post('/ipr/store', 'IprController@store')->middleware(['status', 'project']);
    Route::get('/ipr/{ipr_level_id}/edit', 'IprController@edit')->middleware(['status', 'project']);
    Route::post('/ipr/{ipr_level_id}/update', 'IprController@update')->middleware(['status', 'project']);
    Route::get('/ipr/{ipr_level_id}/delete', 'IprController@delete')->middleware(['status', 'project']);
    Route::post('/ipr/batch', 'IprController@batch')->middleware(['status', 'project']);

    Route::get('/comments', 'CommentsController@index')->middleware(['status', 'project']);
    Route::get('/comments/by_post/{post_id}', 'CommentsController@indexByPost')->middleware(['status', 'project']);
    Route::get('/comments/{comment_id}/allow', 'CommentsController@allow')->middleware(['status', 'project']);
    Route::get('/comments/{comment_id}/disable', 'CommentsController@disable')->middleware(['status', 'project']);
    Route::get('/comments/{comment_id}/delete', 'CommentsController@delete')->middleware(['status', 'project']);
    Route::post('/comments/batch', 'CommentsController@batch')->middleware(['status', 'project']);

    Route::get('/homeworks', 'HomeworksController@index')->middleware(['status', 'project']);
    Route::get('/homeworks/by_post/{post_id}', 'HomeworksController@indexByPost')->middleware(['status', 'project']);
    Route::get('/homeworks/{comment_id}/check', 'HomeworksController@check')->middleware(['status', 'project']);
    Route::get('/homeworks/{comment_id}/uncheck', 'HomeworksController@uncheck')->middleware(['status', 'project']);
    Route::get('/homeworks/{comment_id}/delete', 'HomeworksController@delete')->middleware(['status', 'project']);
    Route::post('/homeworks/batch', 'HomeworksController@batch')->middleware(['status', 'project']);
    
    Route::get("/sort/{type}/{order_by}/{order}", "AccountController@sort");

    Route::get('/notifications/{not_id}/read', "NotificationsController@read");
    Route::get('/notifications/{not_id}/delete', "NotificationsController@delete");
    
    Route::get('/getresponse', "GetResponseController@index")->middleware(['status', 'project']);
    Route::post('/getresponse/settings', "GetResponseController@settings")->middleware(['status', 'project']);
    Route::post('/getresponse/campaigns', "GetResponseController@campaigns")->middleware(['status', 'project']);
    Route::post('/getresponse/test', "GetResponseController@test")->middleware(['status', 'project']);
    Route::post('/getresponse/sendbylevel', "GetResponseController@sendByLevel")->middleware(['status', 'project']);
});