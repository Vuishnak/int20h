<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api->get('/', function () use ($api) {
    return str_random(32);
});

$api->post('auth', [
    'uses' => 'AuthController@authenticate',
    'as' => 'auth'
]);

$api->group([
    'middleware' => 'cors',
], function ($api) {
    $api->post('posts/store', [
        'uses' => 'PostController@store',
        'as' => 'posts.store'
    ]);
});

$api->group([
    'middleware' => 'jwt.auth',
], function ($api) {
    $api->get('user', [
        'uses' => 'AuthController@getUser',
        'as' => 'auth_user'
    ]);

    $api->post('settings', [
        'uses' => 'ConfigController@update',
        'as' => 'config'
    ]);

    $api->get('logout', [
        'uses' => 'AuthController@logout',
        'as' => 'logout'
    ]);

    // пользователи
    $api->group(['prefix' => 'users'], function ($api) {
        $api->get('/', [
            'uses' => 'UserController@getUsers',
            'as' => 'users.index'
        ]);

        $api->post('store', [
            'uses' => 'UserController@store',
            'as' => 'users.store'
        ]);

        $api->put('update', [
            'uses' => 'UserController@update',
            'as' => 'users.update'
        ]);

        $api->delete('delete', [
            'uses' => 'UserController@delete',
            'as' => 'users.delete'
        ]);
    });

    // теги
    $api->group(['prefix' => 'tags'], function ($api) {
        $api->get('/', [
            'uses' => 'TagController@getTags',
            'as' => 'tags.index'
        ]);

        $api->post('store', [
            'uses' => 'TagController@store',
            'as' => 'tags.store'
        ]);

        $api->put('update', [
            'uses' => 'TagController@update',
            'as' => 'tags.update'
        ]);

        $api->delete('delete', [
            'uses' => 'TagController@delete',
            'as' => 'tags.delete'
        ]);
    });

    // теги
    $api->group(['prefix' => 'posts'], function ($api) {
        $api->get('/', [
            'uses' => 'PostController@getPosts',
            'as' => 'posts.index'
        ]);

        $api->put('update', [
            'uses' => 'PostController@update',
            'as' => 'posts.update'
        ]);

        $api->delete('delete', [
            'uses' => 'PostController@delete',
            'as' => 'posts.delete'
        ]);
    });
});
