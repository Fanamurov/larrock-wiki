<?php

Route::group(['prefix' => 'admin'], function(){
    Route::get('/search', [
        'as' => 'admin.search', 'uses' => 'Larrock\ComponentSearch\AdminSearchController@index'
    ]);
});