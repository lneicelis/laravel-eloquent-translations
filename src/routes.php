<?php
Route::group(
    array(
        'before' => 'admin',
        'prefix' => 'admin/translations/',
    ),
    function(){
        Route::get('{namespace?}', array(
            'uses' => 'TranslationsController@getAll'
        ));

        Route::post('all', array(
            'uses' => 'TranslationsController@postAll'
        ));
//
        Route::post('add', array(
            'uses' => 'TranslationsController@postAdd'
        ));

        Route::post('edit', array(
            'uses' => 'TranslationsController@postEdit'
        ));

        Route::post('find', array(
            'uses' => 'TranslationsController@postFind'
        ));

        Route::post('delete', array(
            'uses' => 'TranslationsController@postDelete'
        ));
    }
);

//Route::group(
//    array(
//        'prefix' => 'pages/'
//    ),
//    function(){
//        Route::get('all', array(
//            'uses' => 'PagesController@getAllPublic'
//        ));
//    }
//);



//Route::when('admin/*', 'admin', array('post'));
