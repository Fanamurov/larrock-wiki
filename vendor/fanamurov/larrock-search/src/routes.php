<?php

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu', 'SiteSearchAdmin']], function(){
    Route::get('/search', [
        'as' => 'admin.search', 'uses' => 'Larrock\ComponentSearch\AdminSearchController@index'
    ]);
});