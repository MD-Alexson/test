<?php

Route::group(['namespace' => 'Shared', 'domain' => config('app.domain')],
    function() {
    
    Route::get("/", "SharedController@index");
    
    Route::post("/store", "SharedController@store");
    Route::get("/delete/{id}", "SharedController@delete");
});