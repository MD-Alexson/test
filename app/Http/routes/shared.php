<?php

Route::group(['namespace' => 'Shared', 'domain' => config('app.domain')],
    function() {
    Route::get('/flash', function(){
        return redirect('/')->with('my_modal', ['s1', 's2']);
    });
    Route::get("/", "SharedController@index");
    Route::get("/contacts", "SharedController@contacts");
    Route::post("/contacts", "SharedController@send");
    Route::get("/plans", "SharedController@plans");
    Route::get("/payment", "SharedController@payment");
    Route::get("/terms", "SharedController@terms");

    Route::post("/register", "AuthController@register");
    Route::get("/register/success/{id}", "AuthController@successView");
    Route::get('/register/{key}', "AuthController@newUserView");
    Route::post('/register/{key}', "AuthController@newUser");

    Route::post("/login", "AuthController@login");
    Route::post("/login_admin", "AuthController@loginAdmin");
    Route::get("/login/password", "AuthController@passwordSendEmailView");
    Route::post("/login/password", "AuthController@passwordSendEmail");
    Route::get('/login/password/{key}', "AuthController@passwordResetView");
    Route::post('/login/password/{key}', "AuthController@passwordReset");
});